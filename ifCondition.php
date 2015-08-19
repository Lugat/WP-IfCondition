<?

  /**
   * Plugin Name: IfCondition
   * Description: This plugins allows you to write simple conditions inside your wordpress content.
   * Plugin URI: http://www.squareflower.de/
   * Version: 1.0.3
   * Author: Lukas Rydygel
   * Author URI: http://www.squareflower.de/
   */

  class IfCondition
  {
    
    protected static $conditions = array();
    
    protected $condition;
    protected $matched;
    
    protected function __construct($condition)
    {
      $this->condition = trim($condition);
      $this->matched = self::execute($this->condition);
    }
    
    public static function start($condition)
    {
      self::$conditions[] = new self($condition);
    }
    
    public static function end()
    {
      array_pop(self::$conditions);
    }
    
    protected static function current()
    {
      $i = count(self::$conditions);
      return $i === 0 ? null : self::$conditions[$i-1];
    }
    
    public static function execute($condition)
    {
      
      if (!empty($condition)) {
      
        $fn = function() {
          return (bool) @eval('return '.func_get_arg(0).';');
        };

        return $fn($condition);
                
      }
      
      return false;
      
    }
    
    public static function matched()
    {
      $condition = self::current();
      return isset($condition) ? $condition->matched : false;
    }
    
  }
  
  function do_condition($string)
  {

    return preg_replace_callback('/\[if condition=\"(.*?)\"\](.*?)\[\/if]/s', function($matches) {

      IfCondition::start($matches[1]);
            
      if (has_shortcode($matches[2], 'then') || has_shortcode($matches[2], 'else')) {
        $content = do_shortcode($matches[2]);
      } else {
        $content = IfCondition::matched() === true ? do_shortcode($matches[2]) : null;
      }
            
      IfCondition::end();

      return $content;

    }, $string);
    
  }
  
  add_filter('the_content', 'do_condition', 1);
  add_filter('the_excerpt', 'do_condition', 1);
  add_filter('widget_text', 'do_condition', 1);
  
  add_shortcode('then', function($attr, $content) {
    return IfCondition::matched() === true ? $content : null;
  });
  
  add_shortcode('else', function($attr, $content) {
    return IfCondition::matched() === false ? $content : null;
  });