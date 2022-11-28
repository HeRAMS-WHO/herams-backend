apiVersion: v1
kind: Service
metadata:
  name: "<?= env('DEPLOYMENT_NAME') ?>-service"
spec:
  type: ClusterIP
  ports:
    - port: 80
      targetPort: 80
  selector:
    app: "<?= env('DEPLOYMENT_NAME') ?>"
---
apiVersion: apps/v1
kind: Deployment
metadata:
  name: "<?= env('DEPLOYMENT_NAME') ?>"
spec:
  replicas: 1
  selector:
    matchLabels:
      app: "<?= env('DEPLOYMENT_NAME') ?>"
  template:
    metadata:
      labels:
        app: "<?= env('DEPLOYMENT_NAME') ?>"
    spec:
      securityContext:
        fsGroup: 65534
      volumes:
        # Create the shared files volume to be used in both pods
        - name: shared-files
          emptyDir: {}
        - name: database-seed
          emptyDir: {}
        - name: database
          secret:
            secretName: "<?= env('NEEDS_DATABASE') == "true" ? 'database-preview' : 'database' ?>"
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
        # Our PHP-FPM application
        - name: app
          image: ghcr.io/herams-who/herams-backend/app:latest
          imagePullPolicy: Always
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
<?php if (env('NEEDS_DATABASE') == "true") : ?>
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
            - name: nginx-config-volume
              mountPath: /config
<?php if (env('NEEDS_DATABASE') == "true") : ?>
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
              value: "<?= env('COMMIT_SHA') ?>"
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
  name: <?= env('DEPLOYMENT_NAME') ?>-ingress
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
    - host: "<?= env('DEPLOYMENT_NAME') ?>.herams-staging.org"
      http:
        paths:
          - backend:
              service:
                name: "<?= env('DEPLOYMENT_NAME') ?>-service"
                port:
                  number: 80
            pathType: ImplementationSpecific
