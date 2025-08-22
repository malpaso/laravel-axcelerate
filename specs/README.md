# API Documentation

This directory contains local copies of the Axcelerate API documentation for development reference.

## Files

- `axcelerate-api-docs.html` - Complete HTML documentation from https://app.axcelerate.com/apidocs/Export/html
  - Downloaded: 2024-08-22
  - Size: ~832KB
  - Contains full API reference including endpoints, parameters, examples, and response formats

## Usage

Open `axcelerate-api-docs.html` in a web browser to view the complete API documentation locally. This eliminates the need for internet access during development and ensures you're working with a consistent version of the documentation.

## Updating

To update the documentation, run:
```bash
curl -L -o specs/axcelerate-api-docs.html "https://app.axcelerate.com/apidocs/Export/html"
```