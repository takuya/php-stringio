<?php

namespace Takuya\SystemUtil\Stream\StringIO;

class StringIO {
  
  protected $fp;
  
  /**
   * @param string|null $str string
   * @param string|null $fp_type "memory"|"temp"
   */
  public function __construct(string $str='', string $fp_type='memory'){
    $this->fp = fopen(sprintf('php://%s', $fp_type), 'w+');
    $this->write($str);
  }
  
  /**
   * rewind()
   * @return bool
   */
  public function rewind(): bool {
    return rewind($this->fp);
  }
  
  /**
   * fseek()
   * @param $offset int
   * @param $whence int SEEK_END|SEEK_SET|SEEK_CUR
   * @return int
   */
  public function seek( $offset, $whence ): int {
    
    return fseek($this->fp,$offset,$whence);
  }
  
  /**
   * ftell
   * @return false|int
   */
  public function tell(){
    return ftell($this->fp);
  }
  
  /**
   * @return false|resource|null
   */
  public function resource(){
    return !$this->closed() ? $this->fp : null;
  }
  
  /**
   * fclose
   * @return bool
   */
  public function close(): bool {
    return fclose($this->fp);
  }
  
  /**
   * @return bool
   */
  public function closed(): bool {
    return is_resource($this->fp) == null;
  }
  
  /**
   * return trim(fgets($fp)
   * @return string
   */
  public function readline():string{
    return $this->eof() ?: trim($this->gets());
  }
  
  /**
   * fgets
   * @return false|string
   */
  public function gets(){
    return fgets($this->fp);
  }
  
  /**
   * fwrite
   * @param $string
   */
  public function write($string){
    fwrite($this->fp,$string);
  }
  
  /**
   * stream_get_contents()
   * @return false|string
   */
  public function get_contents(){
    rewind($this->fp);
    return stream_get_contents($this->fp);
  }
  
  /**
   * feof()
   * @return bool
   */
  public function eof(){
    return feof($this->fp);
  }
  
  /**
   * @return \Generator
   */
  public function lines(){
    $this->rewind();
    while ($line = $this->readline()){
      yield $line;
    }
  }
  
  /**
   * stream_get_meta_data()
   * @return array
   */
  public function get_meta_data():array{
    return stream_get_meta_data($this->fp);
  }
  
  /**
   * @return false|string
   */
  public function __toString () {
    return $this->get_contents();
  }
  
}