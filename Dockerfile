# Use the official PHP image as the base image
FROM php:7.4-apache

# Copy the application files into the container
COPY ./AgeGrade
COPY ./age_grade.php

# Expose port 80
EXPOSE 80

# Define the entry point for the container
CMD ["php", "-S", "0.0.0.0:80"]
