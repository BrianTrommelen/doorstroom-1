<?php
    namespace MusicPlayer\Classes;

    class Song {
        public $name = "";
        public $duration = 0;
        public $genre = "";
        public $artist = "";
        public $album = "";

        function  __construct($name, $duration, $genre, Artist $artist, Album $album) {
            $this->name = $name;
            $this->duration = $duration;
            $this->genre = $genre;
            $this->artist = $artist;
            $this->album = $album;
        }

        // Methods
        function set_name($name) {
            $this->name = $name;
        }
        function get_name() {
            return $this->name;
        }
    }

?>