# Use official Node.js image as the base image
FROM node:20.6.0

# Set working directory in the container
WORKDIR /app

# Copy package.json and package-lock.json to the root of the container
COPY protected/react-src/package*.json ./

# Install dependencies in the container
RUN npm install

# Copy the content of the local src directory to the working directory
COPY protected/react-src/ ./

# Specify the command to run on container start
CMD ["npm", "start"]
