<?php

// required to load
require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

/*
 * This file is part of the VCard PHP Class from Jeroen Desloovere.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Tringalama2\VCard\VCard;
use Tringalama2\VCard\VCardException;

/**
 * This class will test our VCard PHP Class which can generate VCards.
 */
class VCardTest extends TestCase
{
    /**
     * @var VCard
     */
    protected $vcard = null;

    /**
     * Data provider for testEmail()
     *
     * @return array
     */
    public function emailDataProvider()
    {
        return [
            [['john@doe.com']],
            [['john@doe.com', 'WORK' => 'john@work.com']],
            [['WORK' => 'john@work.com', 'HOME' => 'john@home.com']],
            [['PREF;WORK' => 'john@work.com', 'HOME' => 'john@home.com']],
        ];
    }

    public function setUp(): void
    {
        // set timezone
        date_default_timezone_set('Europe/Brussels');

        $this->vcard = new VCard();

        $this->firstName = 'Jeroen';
        $this->lastName = 'Desloovere';
        $this->additional = '&';
        $this->prefix = 'Mister';
        $this->suffix = 'Junior';
        
        $this->emailAddress1 = '';
        $this->emailAddress2 = '';

        $this->firstName2 = 'Ali';
        $this->lastName2 = 'ÖZSÜT';

        $this->firstName3 = 'Garçon';
        $this->lastName3 = 'Jéroèn';
    }

    /**
     * Tear down after class
     */
    public function tearDown(): void
    {
        $this->vcard = null;
    }

    public function testAddAddress()
    {
        $this->assertEquals($this->vcard, $this->vcard->addAddress(
          '',
          '88th Floor',
          '555 East Flours Street',
          'Los Angeles',
          'CA',
          '55555',
          'USA'
        ));
      $this->assertStringContainsString('ADR;WORK;POSTAL;CHARSET=utf-8:;88th Floor;555 East Flours Street;Los Angele', $this->vcard->getOutput());
      // Should fold on row 75, so we should not see the full address.
      $this->assertStringNotContainsString('ADR;WORK;POSTAL;CHARSET=utf-8:;88th Floor;555 East Flours Street;Los Angeles;CA;55555;', $this->vcard->getOutput());
    }

    public function testAddBirthday()
    {
        $this->assertEquals($this->vcard, $this->vcard->addBirthday(''));
    }

    public function testAddCompany()
    {
        $this->assertEquals($this->vcard, $this->vcard->addCompany(''));
    }

    public function testAddCategories()
    {
        $this->assertEquals($this->vcard, $this->vcard->addCategories([]));
    }

    public function testAddEmail()
    {
        $this->assertEquals($this->vcard, $this->vcard->addEmail($this->emailAddress1));
        $this->assertEquals($this->vcard, $this->vcard->addEmail($this->emailAddress2));
        $this->assertEquals(2, count($this->vcard->getProperties()));
    }

    public function testAddJobTitle()
    {
        $this->assertEquals($this->vcard, $this->vcard->addJobtitle(''));
    }

    public function testAddRole()
    {
        $this->assertEquals($this->vcard, $this->vcard->addRole(''));
    }

    public function testAddName()
    {
        $this->assertEquals($this->vcard, $this->vcard->addName(''));
    }

    public function testAddNote()
    {
        $this->assertEquals($this->vcard, $this->vcard->addNote(''));
    }

    public function testAddPhoneNumber()
    {
        $this->assertEquals($this->vcard, $this->vcard->addPhoneNumber(''));
        $this->assertEquals($this->vcard, $this->vcard->addPhoneNumber(''));
        $this->assertEquals(2, count($this->vcard->getProperties()));
    }

    public function testAddPhotoWithJpgPhoto()
    {
        $return = $this->vcard->addPhoto(__DIR__ . '/image.jpg', true);

        $this->assertEquals($this->vcard, $return);
    }

    public function testAddPhotoWithRemoteJpgPhoto()
    {
        $return = $this->vcard->addPhoto(
            'https://raw.githubusercontent.com/jeroendesloovere/vcard/master/tests/image.jpg',
            true
        );

        $this->assertEquals($this->vcard, $return);
    }

    /**
     * Test adding remote empty photo
     *
     * @expectedException Exception
     * @expectedExceptionMessage Returned data is not an image.
     */
    public function testAddPhotoWithRemoteEmptyJpgPhoto()
    {
        $this->expectException(VCardException::class);
        $this->expectErrorMessage('Returned data is not an image.');
        $this->vcard->addPhoto(
            'https://raw.githubusercontent.com/jeroendesloovere/vcard/master/tests/empty.jpg',
            true
        );
    }

    public function testAddPhotoContentWithJpgPhoto()
    {
        $return = $this->vcard->addPhotoContent(file_get_contents(__DIR__ . '/image.jpg'));

        $this->assertEquals($this->vcard, $return);
    }

    /**
     * Test adding empty photo
     *
     * @expectedException Exception
     * @expectedExceptionMessage Returned data is not an image.
     */
    public function testAddPhotoContentWithEmptyContent()
    {
        $this->expectException(VCardException::class);
        $this->expectErrorMessage('Returned data is not an image.');
        $this->vcard->addPhotoContent('');
    }

    public function testAddLogoWithJpgImage()
    {
        $return = $this->vcard->addLogo(__DIR__ . '/image.jpg', true);

        $this->assertEquals($this->vcard, $return);
    }

    public function testAddLogoWithJpgImageNoInclude()
    {
        $return = $this->vcard->addLogo(__DIR__ . '/image.jpg', false);

        $this->assertEquals($this->vcard, $return);
    }

    public function testAddLogoContentWithJpgImage()
    {
        $return = $this->vcard->addLogoContent(file_get_contents(__DIR__ . '/image.jpg'));

        $this->assertEquals($this->vcard, $return);
    }

    /**
     * Test adding empty photo
     *
     * @expectedException Exception
     * @expectedExceptionMessage Returned data is not an image.
     */
    public function testAddLogoContentWithEmptyContent()
    {
        $this->expectException(VCardException::class);
        $this->expectErrorMessage('Returned data is not an image.');
        $this->vcard->addLogoContent('');
    }

    public function testAddUrl()
    {
        $this->assertEquals($this->vcard, $this->vcard->addUrl('1'));
        $this->assertEquals($this->vcard, $this->vcard->addUrl('2'));
        $this->assertEquals(2, count($this->vcard->getProperties()));
    }

    /**
     * Test adding local photo using an empty file
     *
     * @expectedException Exception
     * @expectedExceptionMessage Returned data is not an image.
     */
    public function testAddPhotoWithEmptyFile()
    {
        $this->expectException(VCardException::class);
        $this->expectErrorMessage('Returned data is not an image.');
        $this->vcard->addPhoto(__DIR__ . '/emptyfile', true);
    }

    /**
     * Test adding logo with no value
     *
     * @expectedException Exception
     * @expectedExceptionMessage Returned data is not an image.
     */
    public function testAddLogoWithNoValue()
    {
        $this->expectException(VCardException::class);
        $this->expectErrorMessage('Returned data is not an image.');
        $this->vcard->addLogo(__DIR__ . '/emptyfile', true);
    }

    /**
     * Test adding photo with no photo
     *
     * @expectedException Exception
     * @expectedExceptionMessage Returned data is not an image.
     */
    public function testAddPhotoWithNoPhoto()
    {
        $this->expectException(VCardException::class);
        $this->expectErrorMessage('Returned data is not an image.');
        $this->vcard->addPhoto(__DIR__ . '/wrongfile', true);
    }

    /**
     * Test adding logo with no image
     *
     * @expectedException Exception
     * @expectedExceptionMessage Returned data is not an image.
     */
    public function testAddLogoWithNoImage()
    {
        $this->expectException(VCardException::class);
        $this->expectErrorMessage('Returned data is not an image.');
        $this->vcard->addLogo(__DIR__ . '/wrongfile', true);
    }

    /**
     * Test charset
     */
    public function testCharset()
    {
        $charset = 'ISO-8859-1';
        $this->vcard->setCharset($charset);
        $this->assertEquals($charset, $this->vcard->getCharset());
    }

    /**
     * Test Email
     *
     * @dataProvider emailDataProvider $emails
     */
    public function testEmail($emails = [])
    {
        foreach ($emails as $key => $email) {
            if (is_string($key)) {
                $this->vcard->addEmail($email, $key);
            } else {
                $this->vcard->addEmail($email);
            }
        }

        foreach ($emails as $key => $email) {
            if (is_string($key)) {
                $this->assertStringContainsString('EMAIL;INTERNET;' . $key . ':' . $email, $this->vcard->getOutput());
            } else {
                $this->assertStringContainsString('EMAIL;INTERNET:' . $email, $this->vcard->getOutput());
            }
        }
    }

    /**
     * Test first name and last name
     */
    public function testFirstNameAndLastName()
    {
        $this->vcard->addName(
            $this->lastName,
            $this->firstName
        );

        $this->assertEquals('jeroen-desloovere', $this->vcard->getFilename());
    }

    /**
     * Test full blown name
     */
    public function testFullBlownName()
    {
        $this->vcard->addName(
            $this->lastName,
            $this->firstName,
            $this->additional,
            $this->prefix,
            $this->suffix
        );

        $this->assertEquals('mister-jeroen-desloovere-junior', $this->vcard->getFilename());
    }

    /**
     * Test multiple birthdays
     *
     * @expectedException Exception
     */
    public function testMultipleBirthdays()
    {
        $this->expectException(VCardException::class);
        $this->expectErrorMessage('You can only set "birthday" once.');
        $this->assertEquals($this->vcard, $this->vcard->addBirthday('1'));
        $this->assertEquals($this->vcard, $this->vcard->addBirthday('2'));
    }

    /**
     * Test multiple categories
     *
     * @expectedException Exception
     */
    public function testMultipleCategories()
    {
        $this->expectException(VCardException::class);
        $this->expectErrorMessage('You can only set "categories" once.');
        $this->assertEquals($this->vcard, $this->vcard->addCategories(['1']));
        $this->assertEquals($this->vcard, $this->vcard->addCategories(['2']));
    }

    /**
     * Test multiple companies
     *
     * @expectedException Exception
     */
    public function testMultipleCompanies()
    {
        $this->expectException(VCardException::class);
        $this->expectErrorMessage('You can only set "company" once.');
        $this->assertEquals($this->vcard, $this->vcard->addCompany('1'));
        $this->assertEquals($this->vcard, $this->vcard->addCompany('2'));
    }

    /**
     * Test multiple job titles
     *
     * @expectedException Exception
     */
    public function testMultipleJobtitles()
    {
        $this->expectException(VCardException::class);
        $this->expectErrorMessage('You can only set "jobtitle" once.');
        $this->assertEquals($this->vcard, $this->vcard->addJobtitle('1'));
        $this->assertEquals($this->vcard, $this->vcard->addJobtitle('2'));
    }

    /**
     * Test multiple roles
     *
     * @expectedException Exception
     */
    public function testMultipleRoles()
    {
        $this->expectException(VCardException::class);
        $this->expectErrorMessage('You can only set "role" once.');
        $this->assertEquals($this->vcard, $this->vcard->addRole('1'));
        $this->assertEquals($this->vcard, $this->vcard->addRole('2'));
    }

    /**
     * Test multiple names
     *
     * @expectedException Exception
     */
    public function testMultipleNames()
    {
        $this->expectException(VCardException::class);
        $this->expectErrorMessage('You can only set "name" once.');
        $this->assertEquals($this->vcard, $this->vcard->addName('1'));
        $this->assertEquals($this->vcard, $this->vcard->addName('2'));
    }

    /**
     * Test multiple notes
     *
     * @expectedException Exception
     */
    public function testMultipleNotes()
    {
        $this->expectException(VCardException::class);
        $this->expectErrorMessage('You can only set "note" once.');
        $this->assertEquals($this->vcard, $this->vcard->addNote('1'));
        $this->assertEquals($this->vcard, $this->vcard->addNote('2'));
    }

    /**
     * Test special first name and last name
     */
    public function testSpecialFirstNameAndLastName()
    {
        $this->vcard->addName(
            $this->lastName2,
            $this->firstName2
        );

        $this->assertEquals('ali-ozsut', $this->vcard->getFilename());
    }

    /**
     * Test special first name and last name
     */
    public function testSpecialFirstNameAndLastName2()
    {
        $this->vcard->addName(
            $this->lastName3,
            $this->firstName3
        );

        $this->assertEquals('garcon-jeroen', $this->vcard->getFilename());
    }

    /**
     * Test multiple labels
     */
    public function testMultipleLabels()
    {
        $this->assertSame($this->vcard, $this->vcard->addLabel('My label'));
        $this->assertSame($this->vcard, $this->vcard->addLabel('My work label', 'WORK'));
        $this->assertSame(2, count($this->vcard->getProperties()));
        $this->assertStringContainsString('LABEL:My label', $this->vcard->getOutput());
        $this->assertStringContainsString('LABEL;WORK:My work label', $this->vcard->getOutput());
    }

    public function testChunkSplitUnicode()
    {
        $class_handler  = new \ReflectionClass('Tringalama2\VCard\VCard');
        $method_handler = $class_handler->getMethod('chunk_split_unicode');
        $method_handler->setAccessible(true);

        $ascii_input="Lorem ipsum dolor sit amet,";
        $ascii_output = $method_handler->invokeArgs(new VCard(), [$ascii_input,10,'|']);
        $unicode_input='Τη γλώσσα μου έδωσαν ελληνική το σπίτι φτωχικό στις αμμουδιές του Ομήρου.';
        $unicode_output = $method_handler->invokeArgs(new VCard(), [$unicode_input,10,'|']);

        $this->assertEquals(
            "Lorem ipsu|m dolor si|t amet,|",
            $ascii_output);
        $this->assertEquals(
            "Τη γλώσσα |μου έδωσαν| ελληνική |το σπίτι φ|τωχικό στι|ς αμμουδιέ|ς του Ομήρ|ου.|",
            $unicode_output);
    }
}
