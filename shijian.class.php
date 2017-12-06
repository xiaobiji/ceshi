<?php
class Event 
{ 
    //设置静态变量
    protected static $listens= array(); 
    //设置静态listen方法
    public static function listen($event, $callback, $once=false){ 
        //检测callback参数能否被调用
        if(!is_callable($callback)) return false; 
        //设置$listens[$event]数组，值为callback，和once
        self::$listens[$event][]    = array('callback'=>$callback, 'once'=>$once); 
        return true; 
    } 
      //设置静态one方法
    public static function one($event, $callback){ 
        //在one方法中调用listen方法，参数为one方法参数
        return self::listen($event, $callback, true); 
    } 
      //设置remove方法，参数为$index
    public static function remove($event, $index=null){ 
        //$index为空，删除$listen数组
        if(is_null($index)) 
            unset(self::$listens[$event]); 
        else
             unset(self::$listens[$event][$index]); 
    } 
      
    public static function trigger(){ 
        if(!func_num_args()) return; 
        $args = func_get_args(); 

        $event  = array_shift($args); 

        if(!isset(self::$listens[$event])) return false; 

        foreach((array) self::$listens[$event] as $index=>$listen){ 

            $callback               = $listen['callback']; 

            $listen['once'] && self::remove($event, $index); 

            call_user_func_array($callback, $args); 
        } 
    } 
}

// 增加监听walk事件 
Event::listen('walk', function(){ 
    echo "walk" . "</br>"; 
}); 
// 增加监听walk一次性事件 
Event::listen('walk', function(){ 
    echo "listening" . "</br>"; 
}, true); 
// 触发walk事件 
Event::trigger('walk') . "</br>"; 
/* 
I am walking... 
I am listening... 
*/
 Event::trigger('walk') . "</br>"; 
/* 
I am walking... 
*/
   
Event::one('say', function($name=''){ 
    echo "I am {$name}n" . "</br>"; 
}); 
  
Event::trigger('say', 'deeka') . "</br>"; // 输出 I am deeka 
Event::trigger('say', 'deeka') . "</br>"; // not run 
  
class Foo 
{ 
    public function bar(){ 
        echo "Foo::bar() is calledn" . "</br>"; 
    } 
      
    public function test(){ 
        echo "Foo::foo() is called, agrs:".json_encode(func_get_args())."n" . "</br>"; 
    } 
} 
  
$foo    = new Foo; 
  
Event::listen('bar', array($foo, 'bar')); 
Event::trigger('bar') . "</br>"; 
  
Event::listen('test', array($foo, 'test')); 
Event::trigger('test', 1, 2, 3) . "</br>"; 
  
class Bar 
{ 
    public static function foo(){ 
        echo "Bar::foo() is calledn" . "</br>"; 
    } 
} 
  
Event::listen('bar1', array('Bar', 'foo')); 
Event::trigger('bar1') . "</br>"; 
  
Event::listen('bar2', 'Bar::foo'); 
Event::trigger('bar2') . "</br>"; 
  
function bar(){ 
    echo "bar() is calledn" . "</br>"; 
} 
  



啦啦啦啦啦啦啦啦啦啦啦啦啦啦啦





Event::listen('bar3', 'bar') . "</br>"; 
Event::trigger('bar3') . "</br>";