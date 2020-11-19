<?php
    namespace MusicPlayer\Services;

    require('../database/Config.php');
    require('../database/Database.php');

    require_once('../classes/Artist.php');
    require_once('../classes/Album.php');
    require_once('../classes/Song.php');

    use MusicPlayer\Database\Database;

    use MusicPlayer\Classes\Artist;
    use MusicPlayer\Classes\Album;
    use MusicPlayer\Classes\Song;

    class Dao {
        public $database;

        function  __construct($pdo) {
            $this->database = new Database($pdo);
        }
       
        public function get_artists() {
            $statement = "SELECT * FROM artist";
            $rows = $this->database->getData($statement);

            foreach($rows as $artist) {
                $artists[] = new Artist($artist['name'], $artist['age'], $artist['bio'], $artist['avatar']);
            }

            return $artists;
        }

        public function get_songs_from_album($album) {
            $statement = "SELECT song.name, song.duration, song.genre, song.artist_id, artist.name AS artist_name, album.name AS album_name FROM song INNER JOIN artist ON artist.id = song.artist_id INNER JOIN album ON album.id = song.album_id WHERE album.name = '".$album."'";
            $rows = $this->database->getData($statement);

            foreach($rows as $song) {
                $songs[] = new Song($song['name'], $song['duration'], $song['genre'],  $this->get_artist_by_name($song['artist_name']), $this->get_album_by_name($song['album_name']));
            }

            return $songs;
        }

        public function get_albums($ammount) {
            if($ammount == null) {
                $ammount_str = '';
            } else {
                $ammount_str = 'LIMIT '. $ammount;
            }

            $statement = "SELECT album.name, album.description, artist.name AS artist_name, album.release_date, album.album_cover FROM album INNER JOIN artist on artist.id = album.artist_id $ammount_str";
            $rows = $this->database->getData($statement);

            foreach($rows as $album) {
                $albums[] = new Album($album['name'], $album['description'], $this->get_artist_by_name($album['artist_name']), $album['release_date'], $album['album_cover']); 
            }
            
            return $albums;
        }

        public function get_albums_by_random($ammount) {
            if($ammount == null) {
                $ammount_str = '';
            } else {
                $ammount_str = 'LIMIT '. $ammount;
            }

            $statement = "SELECT album.name, album.description, artist.name AS artist_name, album.release_date, album.album_cover FROM album INNER JOIN artist on artist.id = album.artist_id ORDER BY RAND() $ammount_str";
            $rows = $this->database->getData($statement);

            foreach($rows as $album) {
                $albums[] = new Album($album['name'], $album['description'], $this->get_artist_by_name($album['artist_name']), $album['release_date'], $album['album_cover']); 
            }
            
            return $albums;
        }

        public function get_albums_by_release_order($ammount) {
            if($ammount == null) {
                $ammount_str = '';
            } else {
                $ammount_str = 'LIMIT '. $ammount;
            }

            $statement = "SELECT album.name, album.description, artist.name AS artist_name, album.release_date, album.album_cover FROM album INNER JOIN artist on artist.id = album.artist_id ORDER BY album.release_date DESC $ammount_str";
            $rows = $this->database->getData($statement);

            foreach($rows as $album) {
                $albums[] = new Album($album['name'], $album['description'], $this->get_artist_by_name($album['artist_name']), $album['release_date'], $album['album_cover']); 
            }
            
            return $albums;
        }

        function get_artist_by_name($name) {
            $statement = "SELECT * FROM artist WHERE name = '".$name."'";
            $row = $this->database->getData($statement);
            $artist = new Artist($row[0]['name'], $row[0]['age'], $row[0]['bio'], $row[0]['avatar']);
            
            return $artist;
        }

        function get_album_by_name($name) {
            $statement = "SELECT album.name, album.description, artist.name AS artist_name, album.release_date, album.album_cover FROM album INNER JOIN artist on album.artist_id = album.artist_id WHERE album.name = '".$name."'";
            $row = $this->database->getData($statement);
            $album = new Album($row[0]['name'], $row[0]['description'], $this->get_artist_by_name($row[0]['artist_name']), $row[0]['release_date'], $row[0]['album_cover']);
            
            return $album;
        }
    }
?>