<?php

namespace oheso;

/**
 * autoload
 */
class autoload
{
    public function __construct()
    {
        spl_autoload_register(function($class){
            // クラス名を \ を区切りにして配列化する
            $class_path = explode('\\', $class);
        
            // クラスのネームスペースが一致するかチェック
            if ($class_path[0] === __NAMESPACE__) {
                // ファイルパスを作るために、namespace のルートをこのディレクトリに変更
                $class_path[0] = __DIR__;
        
                // 配列を / でつないでファイル名を作る
                $file_path = implode(DIRECTORY_SEPARATOR, $class_path) . '.php';
        
                // 対象ファイルが実在すれば読み込む
                if (file_exists($file_path)) {
                    require_once $file_path;
                }
            }
        });
    }
}