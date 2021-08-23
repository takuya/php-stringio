<?php

namespace Tests\Units;

use Tests\TestCase;
use Takuya\SystemUtil\Stream\StringIO\StringIO;

class StriongIOTest extends TestCase {

  public function test_sio_close_write() {
    
    $sio = new StringIO( "---------" );
    $r = $sio->close();
    $this->assertTrue($r);
    $closed = $sio->closed();
    $this->assertTrue($closed);
    //
    if ( version_compare(PHP_VERSION, '8.0.0', '>=') ){
      $this->expectError();
      $sio->close();
    }elseif (version_compare(PHP_VERSION, '7.0.0', '>=')){
      $this->expectWarning();
      $sio->close();
    }
    
  }
  public function test_write_10M(){
    $size = 1024*1000*10;
    $sio = new StringIO('');
    for ($cnt=0;$cnt<$size;$cnt++){
      $sio->write("\n");
    }
    $sio->seek(0, SEEK_END);
    $pos = $sio->tell();
    $this->assertEquals($size,$pos);
  }
  
  public function test_stringio_construct(){
    $this->assertTrue(is_object(new StringIO()));
    $this->assertTrue((new StringIO())->get_meta_data()['uri']=="php://memory");
    $this->assertTrue((new StringIO())->get_contents() == '');
    //
    $str = 'aaaa';
    $this->assertEquals($str, (new StringIO($str))->get_contents());
    $this->assertTrue((new StringIO($str, 'temp'))->get_meta_data()['uri']=="php://temp");
  }
  public function test_stringio_rewind(){
  
    $sio = new StringIO('');
    $sio->write('aaaaaaaa');
    $ret = $sio->rewind();
    $this->assertTrue($ret);
  
  }
  public function test_stringio_seek(){
  
    $sio = new StringIO('');
    $sio->write('aaaaaaaa');
    $ret = $sio->seek(0,SEEK_END);
    $this->assertEquals(0,$ret);
    $ret = $sio->seek(0,SEEK_SET);
    $this->assertEquals(0,$ret);
    $ret = $sio->seek(1,SEEK_CUR);
    $this->assertEquals(0,$ret);
  
  }
  public function test_stringio_tell(){
    $sio = new StringIO('');
    $sio->write('12345678');
    $ret = $sio->tell();
    $this->assertEquals(8,$ret);
  }
  public function test_stringio_resource(){
    $sio = new StringIO('');
    $this->assertEquals(true,is_resource($sio->resource()));
  
  }
  public function test_stringio_close(){
    $sio = new StringIO('');
    $ret = $sio->close();
    $this->assertTrue($ret);
    $this->assertEquals(false,$sio->resource());
  }
  public function test_stringio_closed(){
    $sio = new StringIO('');
    $ret = $sio->close();
    $this->assertTrue($ret);
    $this->assertTrue($sio->closed());
  
  }
  public function test_stringio_readline(){
    $list = [1,2,3];
    $sio = new StringIO(join("\n",$list));
    $sio->rewind();
    foreach ( $list as $v ) {
      $this->assertEquals($v,$sio->readline());
    }
  }
  public function test_stringio_gets(){
    $list = [1,2,3];
    $sio = new StringIO(join("\n",$list));
    $sio->write("\n");
    $sio->rewind();
    foreach ( $list as $v ) {
      $this->assertEquals($v."\n",$sio->gets());
    }
  }
  public function test_stringio_write(){
    $sio = new StringIO();
    $sio->write("\n");
    $this->assertEquals("\n",$sio->get_contents());
  }
  public function test_stringio_get_contents(){
    $sio = new StringIO("\n");
    $this->assertEquals("\n",$sio->get_contents());
  }
  public function test_stringio_eof(){
    $sio = new StringIO("aaaa\n");
    $sio->get_contents();
    $this->assertTrue($sio->eof());
    $sio->rewind();
    $this->assertFalse($sio->eof());
  
  }
  public function test_stringio_lines(){
    for ($cnt=0;$cnt<10*10;$cnt++){
      $str = str_repeat("--\n",$cnt);
      $sio = new StringIO($str);
      $this->assertEquals($cnt,substr_count($str, "\n"));
      $this->assertEquals($cnt,sizeof(iterator_to_array($sio->lines())));
    }
  
  }
  public function test_stringio_get_meta_data(){
    $sio = new StringIO( "---------" );
    $sio->write("aaaa\n");
    $ret =$sio->get_meta_data();
  
    $this->assertTrue($ret['seekable']);
    $this->assertFalse($ret['eof']);
    $this->assertTrue($ret['blocked']);
  }
  public function test_stringio___toString(){
    $str = 'aaa';
    $sio = new StringIO( $str );
    $this->assertEquals($str,$str, "".$sio);
    
  }
  
}