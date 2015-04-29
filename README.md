# MSN Log Parser

[![License](https://poser.pugx.org/tomzx/msn-log-parser/license.svg)](https://packagist.org/packages/tomzx/msn-log-parser)
[![Latest Stable Version](https://poser.pugx.org/tomzx/msn-log-parser/v/stable.svg)](https://packagist.org/packages/tomzx/msn-log-parser)
[![Latest Unstable Version](https://poser.pugx.org/tomzx/msn-log-parser/v/unstable.svg)](https://packagist.org/packages/tomzx/msn-log-parser)
[![Build Status](https://img.shields.io/travis/tomzx/msn-log-parser.svg)](https://travis-ci.org/tomzx/msn-log-parser)
[![Code Quality](https://img.shields.io/scrutinizer/g/tomzx/msn-log-parser.svg)](https://scrutinizer-ci.com/g/tomzx/msn-log-parser/code-structure)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/tomzx/msn-log-parser.svg)](https://scrutinizer-ci.com/g/tomzx/msn-log-parser)
[![Total Downloads](https://img.shields.io/packagist/dt/tomzx/msn-log-parser.svg)](https://packagist.org/packages/tomzx/msn-log-parser)

MSN Log Parser is a small library which aims to provide a way to parse MSN Messenger logs (both text and HTML) and convert the logs into a format that is easier to digest (JSON).

## Notes

### Text format

The library currently assumes that it will be fed text logs encoded using Windows-1252.

### HTML format

The library currently assumes that it will be fed HTML logs encoded using UTF-16LE.

## License

The code is licensed under the [MIT license](http://choosealicense.com/licenses/mit/). See [LICENSE](LICENSE).