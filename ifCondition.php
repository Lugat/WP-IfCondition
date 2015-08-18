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
    protected $params;
    protected $match;
    
    protected function __construct($condition, array $params = array())
    {
      $this->condition = trim($condition);
      $this->params = $params;
      $this->match = self::execute($this->condition, $this->params);
    }
    
    public static function start($condition, array $params = array())
    {
      self::$conditions[] = new self($condition, $params);
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
    
    public static function execute($condition, array $params = array())
    {
      
      if (!empty($condition)) {
      
        $fn = function() {
          extract(func_get_arg(1));
          return (bool) @eval('return '.func_get_arg(0).';');
        };

        return $fn($condition, $params);
                
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
        
    $params = shortcode_atts(array(
      'condition' => null
    ), $atts);
    
    $condition = $params['condition'];
    unset($params['condition']);
    
    IfCondition::start($condition, $params);
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