<?php

/**
 * Offline TextUi UploadListener Tests
 *
 * @version $Id: UploadListener.php 537 2008-12-09 23:32:59Z edwardotis $
 * @copyright 2005
 */

// THIS TEST SUITE IS INCOMPLETE!

class Phlickr_Tests_Offline_TextUi_UploadListener extends PHPUnit_Framework_TestCase {
    protected $listener, $fileName, $file;

    function setUp() {
        $this->fileName = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'output.txt';
        $this->file = fopen($this->fileName, 'w+');
        $this->assertTrue($this->file !== false, 'file was not opened.');
        $this->listener = new Phlickr_TextUi_UploaderListener($this->file);
    }

    function tearDown() {
        fclose($this->file);
        unlink($this->fileName);

        unset($this->file);
        unset($this->fileName);
        unset($this->listener);
    }

    function testConstructor() {
        $this->assertNotNull($this->listener);
    }

    function testBeforeUpload() {
        $this->listener->beforeUpload();
        rewind($this->file);
        $result = stream_get_contents($this->file);
        $this->assertEquals("Begining upload...\n", $result);
    }

      function testAfterUpload_NoPhotos() {
        $this->listener->afterUpload(array());

        rewind($this->file);
        $result = stream_get_contents($this->file);
        $this->assertEquals("No photos were uploaded.\n", $result);
    }

    function testAfterUpload_WithPhotos() {
        $expected = "All done! If you care to make some changes:\n";
        $expected .= Phlickr_Uploader::buildEditUrl(array(1,3,99)) . "\n";
        $this->listener->afterUpload(array(1=>'photo?',3=>'photo?',99=>'photo?'));

        rewind($this->file);
        $result = stream_get_contents($this->file);
        $this->assertEquals($expected, $result);
    }

    //function testAfterCreatePhotoset() {}

    function testBeforeFileUpload() {
        $fileName = tempnam('/tmp','foo');
        $expected = "Uploading " . basename($fileName) . "...\n";
        $this->listener->beforeFileUpload($fileName);

        rewind($this->file);
        $result = stream_get_contents($this->file);
        $this->assertEquals($expected, $result);
    }

    //function testAfterFileUpload() {}

    //function testFailedFileUpload() {}
}
