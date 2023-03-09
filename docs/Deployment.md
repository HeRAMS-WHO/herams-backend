# Documentation for deployment to the environment

## LimeSurvey

Limesurvey (LS) and its database run on a DO Droplet.
We use a very old version of LS with several plugins.
The LS installation lives in `/home/limesurvey/sites/ls.herams.org`. 
Make sure to switch to that user after logging in to the server: `sudo -i limesurvey`.
The plugins live in the `plugins/` directory and most of them are checked out git repositories.
When making changes just checkout the new tag and it will instantly deploy the new version.
Be sure to run `composer install` from within the plugin directory afterwards.

## Application (PROD)

The application is deployed via Kubernetes. Deployment is done automatically using a CI job.
Any push to the `k8s-prod` branch will result in a new deployment.
All configuration, including secrets, are stored in the public repository.

Secrets are encrypted using [Sealed Secrets Controller](https://sealed-secrets.netlify.app/).
All configuration k8s can be found here: https://github.com/HeRAMS-WHO/herams-backend/tree/surveyjs-parser/k8s
The specs in the `shared/` folder are used by both PROD and STAGING


## Application (STAGING)

Any push to the `v1` branch will result in an automatic deployment to `herams-staging.org`.

## Preview branches

Any PR will result in an auto generated preview domain. If the PR contains migrations a new database is created from the
test data set. If it does not contain migrations it is hooked up to the staging database instead.
