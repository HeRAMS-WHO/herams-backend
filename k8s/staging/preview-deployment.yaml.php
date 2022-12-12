apiVersion: v1
kind: Service
metadata:
  name: "<?= getenv('DEPLOYMENT_NAME') ?>-service"
spec:
  type: ClusterIP
  ports:
    - protocol: TCP
      name: http
      port: 80
      targetPort: 80
    - port: 3306
      name: database
      targetPort: 3306
  selector:
    app: "<?= getenv('DEPLOYMENT_NAME') ?>"
---
apiVersion: v1
kind: Service
metadata:
  name: "<?= getenv('DEPLOYMENT_NAME') ?>-api"
spec:
  type: ClusterIP
  ports:
    - protocol: TCP
      name: http
      port: 80
      targetPort: 80
  selector:
    app: "<?= getenv('DEPLOYMENT_NAME') ?>-api"
---
apiVersion: apps/v1
kind: Deployment
metadata:
  name: "<?= getenv('DEPLOYMENT_NAME') ?>-api"
spec:
  replicas: 1
  selector:
    matchLabels:
      app: "<?= getenv('DEPLOYMENT_NAME') ?>-api"
  template:
    metadata:
      labels:
        app: "<?= getenv('DEPLOYMENT_NAME') ?>-api"
    spec:
      securityContext:
        fsGroup: 65534
      volumes:
        # Create the shared files volume to be used in both containers
        - name: shared
          emptyDir: {}
        - name: database
          secret:
            secretName: "<?= getenv('NEEDS_DATABASE') == "true" ? 'database-preview' : 'database' ?>"
        - name: app
          secret:
            secretName: app
        - name: smtp
          secret:
            secretName: smtp
        - name: mailchimp
          secret:
            secretName: mailchimp
        - name: nginx-config-volume
          configMap:
            name: nginx-config
      containers:
        - name: api
          image: ghcr.io/herams-who/herams-backend/api:latest
          imagePullPolicy: Always
          env:
            - name: database_host
              value: "<?= getenv('DEPLOYMENT_NAME') ?>-service"
            - name: database_name
              value: "preview"
          volumeMounts:
            - name: database
              mountPath: "/run/secrets/database"
            - name: smtp
              mountPath: "/run/secrets/smtp"
            - name: mailchimp
              mountPath: "/run/secrets/mailchimp"
            - name: app
              mountPath: "/run/secrets/app"
            - name: shared
              mountPath: /shared
        # Our nginx container, which uses the configuration declared above,
        # along with the files shared with the PHP-FPM app.
        - name: nginx
          image: ghcr.io/herams-who/docker/nginx-api:latest
          livenessProbe:
            httpGet:
              path: /health/status
              port: 80
          ports:
            - containerPort: 80
          volumeMounts:
            - name: shared
              mountPath: /shared
---
apiVersion: apps/v1
kind: Deployment
metadata:
  name: "<?= getenv('DEPLOYMENT_NAME') ?>"
spec:
  replicas: 1
  selector:
    matchLabels:
      app: "<?= getenv('DEPLOYMENT_NAME') ?>"
  template:
    metadata:
      labels:
        app: "<?= getenv('DEPLOYMENT_NAME') ?>"
    spec:
      securityContext:
        fsGroup: 65534
      volumes:
        # Create the shared files volume to be used in both containers
        - name: shared
          emptyDir: { }
        - name: shared-files
          emptyDir: {}
        - name: database-seed
          emptyDir: {}
        - name: database
          secret:
            secretName: "<?= getenv('NEEDS_DATABASE') == 'true' ? 'database-preview' : 'database' ?>"
        - name: app
          secret:
            secretName: app
        - name: smtp
          secret:
            secretName: smtp
        - name: mailchimp
          secret:
            secretName: mailchimp
        - name: nginx-config-volume
          configMap:
            name: nginx-config

      containers:
        - name: app
          image: ghcr.io/herams-who/herams-backend/app:latest
          imagePullPolicy: Always
          env:
            - name: database_host
              value: "<?= getenv('DEPLOYMENT_NAME') ?>-service"
            - name: database_name
              value: "preview"
          volumeMounts:
            - name: database
              mountPath: "/run/secrets/database"
            - name: smtp
              mountPath: "/run/secrets/smtp"
            - name: mailchimp
              mountPath: "/run/secrets/mailchimp"
            - name: app
              mountPath: "/run/secrets/app"
            - name: shared-files
              mountPath: /var/www/html
            - name: shared
              mountPath: /shared
<?php if (getenv('NEEDS_DATABASE') == "true") : ?>
            - name: database-seed
              mountPath: /database-seed
<?php endif; ?>
        # Our nginx container, which uses the configuration declared above,
        # along with the files shared with the PHP-FPM app.
        - name: nginx
          image: ghcr.io/herams-who/docker/nginx:latest
          command: ["nginx", "-g", "daemon off;", "-c", "/config/nginx.conf"]
          livenessProbe:
            httpGet:
              path: /status
              port: 80
          ports:
            - containerPort: 80
          volumeMounts:
            - name: shared-files
              mountPath: /var/www/html
            - name: shared
              mountPath: /shared
            - name: nginx-config-volume
              mountPath: /config
<?php if (getenv('NEEDS_DATABASE') == "true") : ?>
        - name: mysql
          image: mysql
          command:
            - /bin/sh
            - "-x"
            - "-c"
            - "until test -f /docker-entrypoint-initdb.d/10_table-structure.sql; do sleep 5; done; sleep 5; exec /entrypoint.sh mysqld"
          volumeMounts:
            - name: database-seed
              mountPath: /docker-entrypoint-initdb.d
          env:
            # this is here to force recreation of the database on every commit
            - name: COMMIT_SHA
              value: "<?= getenv('COMMIT_SHA') ?>"
            - name: MYSQL_DATABASE
              value: preview
            - name: MYSQL_RANDOM_ROOT_PASSWORD
              value: "yes"
            - name: MYSQL_USER
              valueFrom:
                secretKeyRef:
                  name: database-preview
                  key: username
            - name: MYSQL_PASSWORD
              valueFrom:
                secretKeyRef:
                  name: database-preview
                  key: password
          livenessProbe:
            tcpSocket:
              port: 3306
<?php endif; ?>
---
apiVersion: networking.k8s.io/v1
kind: Ingress
metadata:
  name: <?= getenv('DEPLOYMENT_NAME') ?>-ingress
  annotations:
    kubernetes.io/ingress.class: nginx
    cert-manager.io/cluster-issuer: "letsencrypt-prod"

spec:
  tls:
    - hosts:
      - "*.herams-staging.org"
      - herams-staging.org
      secretName: herams-staging.tls
  rules:
    - host: "<?= getenv('DEPLOYMENT_NAME') ?>.herams-staging.org"
      http:
        paths:
          - backend:
              service:
                name: "<?= getenv('DEPLOYMENT_NAME') ?>-api"
                port:
                  number: 80
            path: /api
            pathType: Prefix
          - backend:
              service:
                name: "<?= getenv('DEPLOYMENT_NAME') ?>-service"
                port:
                  number: 80
            path: /
            pathType: Prefix
