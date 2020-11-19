<?php
    namespace MusicPlayer;

    require_once('../classes/Artist.php');
    require_once('../classes/Album.php');
    require_once('../classes/Song.php');

    require_once('../services/Dao.php');
 

    use MusicPlayer\Classes\Artist;
    use MusicPlayer\Classes\Album;
    use MusicPlayer\Classes\Song;

    use MusicPlayer\Services\Dao;

    $artists = array();
  

    // $artist1 = new Artist("Boris Brejcha", 41, "Maker of techno", 'URL');

    // $song1 = new Song("Space Diver Song", 397, "techno", $artist1);

    // $album1= new Album("Space Diver", "Description", $artist1, "(string) date(Y/m/d)", "URL");

    // var_dump($album1);
    echo "<br>";
    

    $dao = new Dao($pdo);
    
    // $db_songs = $dao->get_artists();  
    // $db_songs = $dao->get_albums();
    $db_songs = $dao->get_songs();

    // $db_songs = $dao->get_album_by_name('Space Diver');
    // $db_songs = $dao->get_artist_by_name('Boris Brejcha');

    // var_dump($db_songs);

    foreach($db_songs as $song){
        // echo($song->artist);
        var_dump($song->artist);
    }
?>
