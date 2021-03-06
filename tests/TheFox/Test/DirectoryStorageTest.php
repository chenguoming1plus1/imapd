<?php

namespace TheFox\Test;

use PHPUnit\Framework\TestCase;
use TheFox\Imap\Storage\DirectoryStorage;

class DirectoryStorageTest extends TestCase
{
    public function testGetDirectorySeperator()
    {
        $storage = new DirectoryStorage();

        $this->assertEquals(DIRECTORY_SEPARATOR, $storage->getDirectorySeperator());
    }

    public function testSetPath()
    {
        $path = './tmp/test_data/test_mailbox_' . date('Ymd_His') . '_' . uniqid('', true);

        $storage = new DirectoryStorage();
        $storage->setPath($path);

        $this->assertTrue(file_exists($path));
    }

    public function testFolderExists()
    {
        $path = './tmp/test_data/test_mailbox_' . date('Ymd_His') . '_' . uniqid('', true);

        $storage = new DirectoryStorage();
        //$storage->setPath($path);

        $this->assertFalse($storage->folderExists($path));
    }

    public function testCreateFolder()
    {
        $path = './tmp/test_data/test_mailbox_' . date('Ymd_His') . '_' . uniqid('', true);

        $storage = new DirectoryStorage();

        $this->assertTrue($storage->createFolder($path));
        $this->assertFalse($storage->createFolder($path));
    }
    
    public function testGetMailsCountByFolder(){
        $path = './tmp/test_data/test_mailbox_' . date('Ymd_His') . '_' . uniqid('', true);

        $storage = new DirectoryStorage();
        $storage->setPath($path);
        
        $this->assertEquals(0, $storage->getMailsCountByFolder('.'));
    }
}
