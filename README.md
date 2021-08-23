# StringIO 

![<CircleciTest>](https://circleci.com/gh/takuya/php-stringio.svg?style=svg)

'string' as IO Stream Object. To avoid large string split into large array. 

Needless to say,`SplFileObject('php://memory','w+')` is best, but too much.


## Installation
```
composer requore takuya/php-stringio
```

## Usage
```php
$sio = new StringIO("1234\n");
$sio->write("aaaa\n");
$sio->write("bbbb\n");
$sio->write("cccc\n");
$sio->rewind();
foreach ( $sio->lines() as $line) {
  var_dump($line);
}
$sio->close();
```

### Same to SplFileObject
```php
$sio = new \SplFileObject('php://memory','w+');
$sio->fwrite("1234\n");
$sio->fwrite("aaaa\n");
$sio->fwrite("bbbb\n");
$sio->fwrite("cccc\n");
foreach ( $sio as $line) {
  var_dump(trim($line));
}
unset($sio);
```
[SplFileObject](https://www.php.net/manual/en/class.splfileobject.php) has too much inherited method.

### Differences to SplFileObject

- trim() -- no new line(s such as "\r","\n")
- few methods -- no inherited methods
- f- prefix -- without f-  ( fwrite/write )
- yield  -- generator in lines()
- close -- SplFileObject does not have fclose()

### Methods 

- StringIO#rewind
- StringIO#seek
- StringIO#tell
- StringIO#resource
- StringIO#close
- StringIO#closed
- StringIO#readline
- StringIO#gets
- StringIO#write
- StringIO#get_contents
- StringIO#eof
- StringIO#lines
- StringIO#get_meta_data
- StringIO#__toString





