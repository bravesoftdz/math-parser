# math-parser
Download image from remote host


## Installation

### Composer

The preferred way to install this extension is through [Composer](http://getcomposer.org/).

Either run

```
php composer.phar require dykyi-roman/math-parser "master"
```

or add

```
"dikiypac/math-parser": "master"
```

to the require section of your ```composer.json```

## Usage

```php
    $mp = new mathParser();
    $result = $mp->calc("1+2-(2*2)");
```

## Author

[Dykyi Roman](https://github.com/dykyi-roman/), e-mail: [mr.dukuy@gmail.com](mailto:mr.dukuy@gmail.com)
