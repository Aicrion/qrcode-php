---
title: Contributing
layout: default
---

# Contributing

[← Back to index](index.md)

Contributions are welcome! Please follow this workflow:

1. Fork the [repository](https://github.com/aicrion/qrcode-php)
2. Create a feature branch: `git checkout -b feature/my-feature`
3. Install dependencies: `composer install`
4. Run the test suite: `composer test`
5. Run static analysis: `composer stan`
6. Fix code style: `composer cs-fix`
7. Commit using clear, English commit messages
8. Open a Pull Request describing your change

## Coding Standards

- PHP 8.2+ syntax (readonly properties, enums, `declare(strict_types=1)`)
- One class/interface/enum per file, PSR-4 autoloading
- All public APIs must have PHPDoc blocks and unit tests

Next: [Changelog](changelog.md)
