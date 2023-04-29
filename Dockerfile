# Use the official PHP image as the base image
FROM php

# Copy the application files into the container
COPY AgeGrade ./
COPY index.php ./
# Expose port 80
EXPOSE 80
# Define the entry point for the container
CMD ["php", "-S", "0.0.0.0:80"]
