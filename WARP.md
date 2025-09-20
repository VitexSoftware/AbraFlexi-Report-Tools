# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Project Overview

AbraFlexi-Report-Tools is a set of PHP command-line tools for working with AbraFlexi custom reports. The project provides three main utilities:

- **Report Uploader** (`upreport`) - Upload or compile & upload report files to AbraFlexi
- **Report Extractor** (`repxmlunpacker`) - Create JasperStudio projects from AbraFlexi report installation files
- **Report Preview** (`previewreport`) - Download and preview invoices in specific report formats

## Architecture

### Core Components

- **Namespace**: `AbraFlexi\Report\`
- **Main Class**: `Uploader` (extends `AbraFlexi\Report`) - handles report compilation and upload
- **Dependencies**: Built on top of `spojenet/flexibee` for AbraFlexi integration and `lightools/xml` for XML processing

### Project Structure

```
src/
├── Report/
│   └── Uploader.php          # Core report uploader class
├── upreport.php              # Report uploader CLI script
├── previewreport.php         # Report preview CLI script
├── repxmlunpacker.php        # Report extractor CLI script
└── repxmlpacker.php          # Report packer script
bin/                          # Executable CLI scripts
├── upreport
├── previewreport
├── repxmlunpacker
└── updatejasperclasspath
```

### Key Features

- **Jasper Integration**: Automatically compiles `.jrxml` files to `.jasper` using `jaspercompiler`
- **Multi-file Upload**: Supports uploading main reports with attachments (localization files, logos, etc.)
- **Environment Configuration**: Uses `.env` file for AbraFlexi connection settings
- **Cross-platform**: Works on Linux/Debian with package installation support

## Development Commands

### Setup and Dependencies
```bash
# Install PHP dependencies
make vendor
# or
composer install

# Install from Debian repo (alternative installation)
sudo apt install abraflexi-report-tools
```

### Code Quality and Testing
```bash
# Run static analysis
make static-code-analysis

# Generate PHPStan baseline
make static-code-analysis-baseline

# Run tests
make tests

# Fix coding standards
make cs

# Manual PHPStan with custom config
vendor/bin/phpstan analyse --configuration=phpstan-default.neon.dist --memory-limit=-1
```

### CLI Tool Usage

#### Report Upload
```bash
# Basic syntax
bin/upreport <recordIdent> "<Report Name>" <formInfoCode> <reportfile> [attachments...]

# Example with JRXML (auto-compiled to .jasper)
bin/upreport code:PokladDen "My Report" pokDenik report.jrxml localization.json logo.png

# Example with compiled Jasper
bin/upreport code:TEST formInfoCode report.jasper
```

#### Report Preview
```bash
# Preview specific invoice with report
bin/previewreport code:Test3 code:VF1-0001/2023 en

# Preview first available invoice
bin/previewreport code:Test3
```

#### Report Extraction
```bash
# Extract AbraFlexi reports to JasperStudio project
bin/repxmlunpacker abraflexi-reports-import.xml /path/to/jasper/workspace/
```

## Configuration

### Required Environment Variables
Set these in `.env` file or environment:
- `ABRAFLEXI_URL` - AbraFlexi server URL
- `ABRAFLEXI_LOGIN` - Username for AbraFlexi
- `ABRAFLEXI_PASSWORD` - Password for AbraFlexi  
- `ABRAFLEXI_COMPANY` - Company database identifier

### External Dependencies
- **jaspercompiler** - Command-line JRXML compiler (must be in PATH)
- **AbraFlexi server** - Target AbraFlexi installation
- **xdg-open** - For report preview functionality (Linux)

## Code Architecture Details

### Report Uploader Class

The `AbraFlexi\Report\Uploader` class handles:
- **Jasper Compilation**: Automatically compiles `.jrxml` to `.jasper` files
- **File Attachment**: Manages report files and associated resources
- **AbraFlexi Integration**: Creates/updates report records via API
- **Error Handling**: 404 detection for new report creation

### CLI Script Pattern

All CLI scripts follow this pattern:
1. Load autoloader and initialize constants
2. Parse command line arguments with usage validation
3. Initialize AbraFlexi connection from environment
4. Execute specific functionality
5. Provide status feedback

### File Processing

- **JRXML Files**: Automatically compiled using external `jaspercompiler`
- **Attachments**: Support for multiple file types (JSON, PNG, etc.)
- **XML Processing**: Uses `lightools/xml` for parsing AbraFlexi export files

## Code Quality Tools

### PHPStan Configuration
- **Level**: Uses custom configuration with type coverage requirements
- **Memory**: Unlimited memory for analysis (`--memory-limit=-1`)
- **Baseline**: Supports baseline generation for legacy code

### PHP CS Fixer
- **Config**: `.php-cs-fixer.dist.php` with custom rules
- **Usage**: `make cs` for automatic formatting

### GitHub Actions
- **Validation**: Composer file validation
- **Caching**: Composer dependencies cached
- **Dependencies**: Automatic dependency installation

## Integration Notes

### AbraFlexi API
- Uses `spojenet/flexibee` library for API communication
- Supports record identification via `code:` prefix
- Handles 404 responses for new report creation
- Manages file attachments through `Priloha` class

### Jasper Reports
- Requires external `jaspercompiler` tool
- Supports both `.jrxml` source and `.jasper` compiled formats
- Integrates with AbraFlexi classpath for report compilation