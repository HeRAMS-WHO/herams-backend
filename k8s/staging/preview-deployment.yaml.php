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
        - name: database
          secret:
            secretName: <?= (bool) env('NEEDS_DATABASE') ? 'preview-database' : 'database' ?>
        - name: app
          secret:
            secretName: app
        - name: limesurvey
          secret:
            secretName: limesurvey
        - name: smtp
          secret:
            secretName: smtp
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
            - name: limesurvey
              mountPath: "/run/secrets/limesurvey"
            - name: smtp
              mountPath: "/run/secrets/smtp"
            - name: app
              mountPath: "/run/secrets/app"
            - name: shared-files
              mountPath: /var/www/html

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
<?php if ((bool) env('NEEDS_DATABASE')) : ?>
        - name: mysql
          image: ghcr.io/herams-who/herams-backend/testdb:latest
          env:
            - name: MYSQL_DATABASE
              value: preview
            - name: MYSQL_RANDOM_ROOT_PASSWORD
              value: yes
            - name: MYSQL_USER
              valueFrom:
                secretKeyRef:
                  name: preview-database
                  key: username
            - name: MYSQL_PASSWORD
              valueFrom:
                secretKeyRef:
                  name: preview-database
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
