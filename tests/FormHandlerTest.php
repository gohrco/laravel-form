<?php

namespace Tests;

use Gohrco\LaravelForm\FormHandler;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Tests\BaseTestCase;

class FormHandlerTest extends BaseTestCase
{
    public function testLoadReturnsFormObject()
    {
        $response = FormHandler::load('test');
        $this->assertIsObject($response);
        $this->assertInstanceOf(\Gohrco\LaravelForm\Forms\GenericForm::class, $response);
    }

    public function testBuildReturnsFormObjectSetInContents()
    {
        $form = App::make(FormHandler::class);
        $form = self::setProperty(
            'contents',
            [
                'class' => 'GenericForm',
                'name' => 'Test Form',
                'routeAction' => '/home',
                'fields' => [],
            ],
            $form
        );
        $method = self::getMethod(FormHandler::class, 'build');
        $response = $method->invokeArgs($form, []);
        $this->assertIsObject($response);
        $this->assertInstanceOf(\Gohrco\LaravelForm\Forms\GenericForm::class, $response);
    }

    public function testBuildReturnsDefaultGenericformObject()
    {
        $form = App::make(FormHandler::class);
        $form = self::setProperty(
            'contents',
            [
                'name' => 'Test Form',
                'routeAction' => '/home',
                'fields' => [],
            ],
            $form
        );
        $method = self::getMethod(FormHandler::class, 'build');
        $response = $method->invokeArgs($form, []);
        $this->assertIsObject($response);
        $this->assertInstanceOf(\Gohrco\LaravelForm\Forms\GenericForm::class, $response);
    }

    public function testBuildThrowsExceptionOnBadClassInContents()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Form class Forms\GenericForm could not be located for instantiation');
        $form = App::make(FormHandler::class);
        $form = self::setProperty('contents', ['class' => 'Forms\GenericForm'], $form);
        $method = self::getMethod(FormHandler::class, 'build');
        $response = $method->invokeArgs($form, []);
    }

    public function testCleanFileAppendsYmlExtension()
    {
        $form = App::make(FormHandler::class);
        $method = self::getMethod(FormHandler::class, 'cleanFile');
        $response = $method->invokeArgs($form, ['test']);
        $this->assertEquals('test.yml', $response);
    }

    public function testCleanFileStripsYamlExtension()
    {
        $form = App::make(FormHandler::class);
        $method = self::getMethod(FormHandler::class, 'cleanFile');
        $response = $method->invokeArgs($form, ['test.yaml']);
        $this->assertEquals('test.yml', $response);
    }

    public function testFindLocatesKnownFlatFile()
    {
        $form = App::make(FormHandler::class);
        $method = self::getMethod(FormHandler::class, 'find');
        $response = $method->invokeArgs($form, ['test']);
        $response = self::getProperty('filename', $response);
        $expected = dirname(__DIR__) . '/src/resources/forms/test.yml';
        $this->assertEquals($expected, $response);
    }

    public function testFindLocatesKnownFlatFileInSubfolder()
    {
        $form = App::make(FormHandler::class);
        $method = self::getMethod(FormHandler::class, 'find');
        $response = $method->invokeArgs($form, ['subpath.newfile']);
        $response = self::getProperty('filename', $response);
        $expected = dirname(__DIR__) . '/src/resources/forms/subpath/newfile.yml';
        $this->assertEquals($expected, $response);
    }

    public function testFindThrowsExceptionWhenNotFound()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Formname nonexistant.yml not found');
        $form = App::make(FormHandler::class);
        $method = self::getMethod(FormHandler::class, 'find');
        $response = $method->invokeArgs($form, ['nonexistant']);
    }

    public function testFindClassnameReturnsFoundGenericForm()
    {
        $form = App::make(FormHandler::class);
        $method = self::getMethod(FormHandler::class, 'findClassname');
        $response = $method->invokeArgs($form, ['GenericForm']);
        $this->assertEquals('\Gohrco\LaravelForm\Forms\GenericForm', $response);
    }

    public function testFindClassnameReturnsNonexistantClassIfNamespaced()
    {
        $form = App::make(FormHandler::class);
        $method = self::getMethod(FormHandler::class, 'findClassname');
        $response = $method->invokeArgs($form, ['Dontexist\GenericForm']);
        $this->assertEquals('Dontexist\GenericForm', $response);
    }

    public function testFindClassnameThrowsExceptionWhenClassNotFound()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Form classname DifferentForm not found');
        $form = App::make(FormHandler::class);
        $method = self::getMethod(FormHandler::class, 'findClassname');
        $response = $method->invokeArgs($form, ['DifferentForm']);
    }

    public function testReadReturnsSetsContentsArray()
    {
        $form = App::make(FormHandler::class);
        $form = self::setProperty('filename', dirname(__DIR__) . '/src/resources/forms/test.yml', $form);
        $method = self::getMethod(FormHandler::class, 'read');
        $response = $method->invokeArgs($form, []);
        $response = self::getProperty('contents', $response);
        $this->assertIsArray($response);
        $this->assertArrayHasKey('name', $response);
        $this->assertEquals('Test Form', $response['name']);
    }

    public function testReadThrowsExceptionWhenInvalidYamlFile()
    {
        $this->expectException(\Symfony\Component\Yaml\Exception\ParseException::class);
        $form = App::make(FormHandler::class);
        $method = self::getMethod(FormHandler::class, 'read');
        $response = $method->invokeArgs($form, []);
    }
}
