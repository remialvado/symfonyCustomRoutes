<?php
class StackTest extends PHPUnit_Framework_TestCase
{
    public function testPushAndPop()
    {
        $stack = array();
        assertThat(count($stack), is(0));
 
        array_push($stack, 'foo');
        assertThat($stack[count($stack)-1], is("foo"));
        assertThat(count($stack), is(1));
       
        assertThat(array_pop($stack), is("foo"));
        assertThat(count($stack), is(0));
    }
}
