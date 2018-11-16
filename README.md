# who-herams
WHO - WHE - HeRAMS Nigeria

# Set up developer environment

## Steps
1. Get [Docker](https://docs.docker.com/install/)
2. Get [Docker Compose](https://docs.docker.com/compose/install/)
3. Clone the git repository to your local system
4. Optionally alter .env to suit your preferences
5. Run `docker-compose up serve`

## Result
After taking the above steps you will have everything up and running:
- A database with username `root` and password `secret`, and a user / password / database combo from your `.env` file.
- An application with (invalid) email: `root` and password: `secret`
- A mailcatcher allowing you to inspect mails sent by the system



