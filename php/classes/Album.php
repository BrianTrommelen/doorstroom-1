<?php
    namespace MusicPlayer\Classes;

    class Album {
        public $name = "";
        public $description = "";
        public $songs = [];
        public $created_by = "";
        public $release_date = "";
        public $album_cover_url = "";

        function  __construct($name, $description, Artist $artist, $release_date, $album_cover_url) {
            $this->name = $name;
            $this->description = $description;
            $this->songs = [];
            $this->created_by = $artist;
            $this->release_date = $release_date;
            $this->album_cover_url = $album_cover_url;
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