# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel package that provides an API wrapper for the Axcelerate API. It's built using the Spatie Laravel Package Tools framework and follows standard Laravel package conventions.

**Package Details:**
- Namespace: `malpaso\LaravelAxcelerate`
- Package name: `malpaso/laravel-axcelerate`
- PHP requirement: ^8.3
- Laravel support: ^10.0||^11.0||^12.0

## Axcelerate API Overview

The Axcelerate API is a RESTful API designed for educational and training management systems.

**API Characteristics:**
- Base endpoint: `/api`
- Authentication: Dual token system (`wstoken` and `apitoken` headers required)
- Response format: JSON
- Supported methods: GET, POST, PUT

**Key API Endpoints:**
- **Contact Management**: `/contact/` - Comprehensive personal and professional information
- **Accounting**: 
  - Invoices: `/accounting/invoice/`
  - Credit Notes: `/accounting/creditnote/` 
  - Transactions: `/accounting/transaction/`
- **Agent Management**: `/agent/`

**Implementation Considerations:**
- All requests require both `wstoken` and `apitoken` headers
- API provides comprehensive management for contacts, financial transactions, and organizational data
- Supports custom fields and domain-specific configurations
- Detailed error handling with structured responses

## Common Development Commands

### Testing
- `composer test` - Run the test suite using Pest PHP
- `vendor/bin/pest` - Run tests directly
- `vendor/bin/pest --coverage` - Run tests with coverage report

### Code Quality
- `composer analyse` - Run PHPStan static analysis
- `vendor/bin/phpstan analyse` - Run PHPStan directly
- `composer format` - Format code using Laravel Pint
- `vendor/bin/pint` - Format code directly

### Package Development
- `composer prepare` - Discover package for Testbench development

## Architecture and Structure

### Core Components
- **Main Class**: `src/LaravelAxcelerate.php` - The main package class (currently empty, needs implementation)
- **Service Provider**: `src/LaravelAxcelerateServiceProvider.php` - Registers config, views, migrations, and commands using Spatie Package Tools
- **Facade**: `src/Facades/LaravelAxcelerate.php` - Laravel facade for easy access
- **Command**: `src/Commands/LaravelAxcelerateCommand.php` - Artisan command (signature: `laravel-axcelerate`)

### Configuration
- Config file: `config/axcelerate.php` (currently empty)
- Migration stub: `database/migrations/create_axcelerate_table.php.stub`
- Package publishes config, views, and migrations via the service provider

### Testing Setup
- Uses **Pest PHP** as the testing framework
- Orchestra Testbench for Laravel package testing
- Test base class: `tests/TestCase.php`
- Factory support configured for the package namespace
- Architecture tests included via `tests/ArchTest.php`

### Code Quality Tools
- **PHPStan** level 5 analysis with Laravel-specific rules
- Octane compatibility checking enabled
- **Laravel Pint** for code formatting
- Baseline file: `phpstan-baseline.neon`

## Development Notes

This package is in early development - the main `LaravelAxcelerate` class is currently empty and needs implementation of the Axcelerate API wrapper functionality.

The package follows Spatie's Laravel Package Tools conventions, which means:
- Config, views, and migrations are automatically registered
- The service provider uses the fluent package configuration API
- Standard Laravel package structure is followed

When implementing new features, ensure they align with Laravel package best practices and maintain compatibility with the supported Laravel versions (10.x, 11.x, 12.x).