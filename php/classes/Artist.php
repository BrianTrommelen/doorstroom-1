<?php
    namespace MusicPlayer\Classes;

    class Artist {
        public $name = "";
        public $age = 0;
        public $bio = "";
        public $avatar = "";

        function  __construct($name, $age, $bio, $avatar) {
            $this->name = $name;
            $this->age = $age;
            $this->bio = $bio;
            $this->avatar = $avatar;
        }

        // Methods
        public function set_name($name) {
            $this->name = $name;
        }
        public function get_name() {
            return $this->name;
        }
    }

?>