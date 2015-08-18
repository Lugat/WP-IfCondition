<?

  /**
   * Plugin Name: IfCondition
   * Description: This plugins allows you to write simple conditions inside your wordpress content.
   * Plugin URI: http://www.squareflower.de/
   * Version: 1.0.0
   * Author: Lukas Rydygel
   * Author URI: http://www.squareflower.de/
   */

  class IfCondition
  {
    
    protected static $conditions = array();
    
    protected $condition;
    protected $match;
    
    protected function __construct($condition)
    {
      $this->condition = trim($condition);
      $this->match = self::execute($this->condition);
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
      return isset($condition) ? $condition->match : false;
    }
    
  }
  
  add_shortcode('if', function($atts, $content) {
        
    $atts = shortcode_atts(array(
      'condition' => null
    ), $atts);
    
    IfCondition::start($atts['condition']);
    $content = do_shortcode($content);
    IfCondition::end();
      
    return $content;
    
  });
  
  add_shortcode('then', function($attr, $content) {
    return IfCondition::matched() === true ? $content : null;
  });
  
  add_shortcode('else', function($attr, $content) {
    return IfCondition::matched() === false ? $content : null;
  });