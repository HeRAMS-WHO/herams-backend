# Use official Node.js image as the base image
FROM node:20.6.0

# Arguments for user and group IDs
ARG USER_ID
ARG GROUP_ID

# Create a new user "nodeuser" with the provided UID and GID
RUN if getent passwd nodeuser ; then deluser nodeuser; fi && \
    if getent group nodeuser ; then delgroup nodeuser; fi && \
    addgroup --gid $GROUP_ID nodeuser && \
    adduser --disabled-password --gecos '' --uid $USER_ID --gid $GROUP_ID nodeuser

# Create .npm directory and grant permissions
RUN mkdir -p /.npm && chown $USER_ID:$GROUP_ID /.npm

# Set working directory in the container
WORKDIR /app

# Copy package.json and package-lock.json to the root of the container
COPY protected/react-src/package*.json ./

# Change ownership of /app to nodeuser for permission reasons
RUN chown -R nodeuser:nodeuser /app

# Switch to nodeuser
USER nodeuser

# Install dependencies in the container
RUN npm install

# Copy the content of the local src directory to the working directory
COPY protected/react-src/ ./

# Specify the command to run on container start
CMD ["npm", "start"]
