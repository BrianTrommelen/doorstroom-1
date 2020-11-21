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

  if(!isset($_COOKIE["type"]))
  {
  header("location:login.php");
  }

  $artists = array();

  $dao = new Dao($pdo);

  //Get Artist or Album from URL
  $curr_url = $_SERVER['REQUEST_URI'];
  $curr_object = array_slice(explode('?', rtrim($curr_url, '?')), -2)[0];
  $url_var = array_slice(explode('?', rtrim($curr_url, '?')), -1)[0];
  $curr_item = str_replace('%20', ' ', $url_var);

  if($curr_object == "artist") {
    $db_albums_from_artist = $dao->get_albums_from_artist($curr_item);
    $db_artist = $dao->get_artist_by_name($curr_item);
  } elseif($curr_object == "album") {
    $db_album_from_curr = $dao->get_album_by_name($curr_item);
    $db_songs_from_curr = $dao->get_songs_from_album($curr_item);
  } else {
    $db_albums_rand = $dao->get_albums_by_random($args = NULL);
  }

  //Get the data
  $db_artists = $dao->get_artists();

  //Catch delete request & Catch form POSTS
  if(isset($_REQUEST['delete'])) {
    $dao->delete_song_by_name($_POST['Song']);
    header("Refresh:0");
  } elseif(isset($_POST['form'])){
    switch ($_POST['form']) {
        case "selected_song":
          $selected_song = $dao->get_song_by_name($_POST['Song']);
          break;

        case "change_song":
          $dao->update_song_by_name($_POST['selected_song'], $_POST['track_name'], $_POST['duration']);
          header("Refresh:0");
          break;

        case "create_song":
          $artist_id = $dao->get_artist_id_by_name($db_album_from_curr->created_by->name);
          $album_id = $dao->get_album_id_by_name($db_album_from_curr->name);
          $dao->create_song($artist_id, $_POST['track_name'], $_POST['duration_new'], $album_id);
          header("Refresh:0");
          break;

        default:
          eader("Refresh:0");
    } 
  }

?>

<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music Player</title>
    <meta name="description" content="The Music Player">
    <link rel="stylesheet" href="/main.14d423e2.css">
</head>

<body class="collection">
  <header class="header">
    <nav class="navbar navbar-expand-lg">
      <div class="navbar-collapse">
        <a href="/" class="header__link">
          <svg class="header__logo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
            <circle class="fill" cx="256" cy="256" r="246" fill=""/>
            <g fill="#000">
              <path d="M382.775 293.97c-3.413 5.834-9.553 9.092-15.858 9.092-3.145 0-6.33-.812-9.242-2.508-48.208-28.177-103.906-32.702-142.147-31.544-42.364 1.292-73.426 9.656-73.736 9.738-9.765 2.664-19.855-3.077-22.535-12.842-2.678-9.765 3.052-19.85 12.811-22.54 1.397-.383 34.828-9.464 81.314-10.995 27.386-.906 53.742 1.004 78.34 5.668 31.157 5.912 59.571 16.287 84.469 30.835 8.746 5.111 11.694 16.345 6.584 25.096zm17.698-54.378c-3.858 0-7.763-.988-11.332-3.077-114.616-66.982-263.294-27.164-264.784-26.749-11.984 3.299-24.37-3.735-27.669-15.719-3.301-11.982 3.734-24.37 15.718-27.669 1.717-.475 42.72-11.61 99.741-13.493 33.592-1.107 65.922 1.235 96.095 6.956 38.21 7.25 73.069 19.973 103.605 37.817 10.731 6.273 14.347 20.052 8.073 30.784-4.179 7.157-11.709 11.15-19.447 11.15zM353.978 353.773c-2.881 4.924-8.063 7.675-13.386 7.675-2.652 0-5.342-.683-7.799-2.12-40.703-23.786-87.722-27.609-120.006-26.626-35.765 1.086-61.986 8.145-62.25 8.218-8.243 2.255-16.762-2.596-19.022-10.84-2.26-8.245 2.571-16.758 10.815-19.028 1.18-.327 29.403-7.992 68.648-9.284 23.113-.765 45.369.847 66.134 4.789 26.299 4.986 50.293 13.742 71.311 26.025 7.386 4.321 9.874 13.806 5.555 21.191z"/></g><path d="M256 512c-68.38 0-132.667-26.628-181.019-74.981C26.628 388.667 0 324.38 0 256c0-51.13 15.028-100.49 43.461-142.745 3.083-4.582 9.297-5.797 13.879-2.714s5.797 9.297 2.714 13.879C33.851 163.363 20 208.862 20 256c0 130.131 105.869 236 236 236s236-105.869 236-236S386.131 20 256 20c-50.354 0-98.401 15.636-138.946 45.217-4.462 3.255-10.718 2.277-13.973-2.185s-2.277-10.717 2.185-13.973C149.257 16.965 201.38 0 256 0c68.38 0 132.667 26.628 181.019 74.981C485.372 123.333 512 187.62 512 256s-26.628 132.667-74.981 181.019C388.667 485.372 324.38 512 256 512z"/><path d="M76.346 97.911c-2.434 0-4.872-.878-6.789-2.661-4.044-3.761-4.307-10.054-.545-14.098l.208-.222c3.802-4.005 10.132-4.169 14.138-.366 4.005 3.803 4.169 10.132.366 14.138-1.979 2.127-4.677 3.209-7.378 3.209zM366.917 313.062c-4.997 0-9.933-1.337-14.275-3.867-46.16-26.979-99.869-31.313-136.811-30.188-40.402 1.232-70.2 9.068-71.449 9.401-15.02 4.099-30.638-4.803-34.768-19.854-4.13-15.056 4.75-30.68 19.797-34.827 3.67-1.005 37.044-9.814 83.643-11.349 28.108-.929 55.204 1.035 80.532 5.838 32.271 6.123 61.76 16.898 87.65 32.025 13.491 7.881 18.056 25.274 10.175 38.771-.001.002-.003.005-.005.008-5.068 8.661-14.451 14.042-24.489 14.042zM226.148 258.85c38.798 0 90.778 6.304 136.574 33.071 1.295.755 2.711 1.141 4.195 1.141 2.961 0 5.729-1.585 7.225-4.138 2.318-3.975.975-9.094-2.995-11.414-23.917-13.975-51.266-23.948-81.289-29.645-23.884-4.529-49.506-6.381-76.145-5.499-45.075 1.484-77.641 10.272-79.005 10.646-4.41 1.216-7.023 5.816-5.807 10.25 1.216 4.431 5.818 7.054 10.259 5.84 1.408-.376 33.375-8.784 76.063-10.086 3.504-.107 7.155-.166 10.925-.166zm174.326-9.258c-5.748 0-11.414-1.538-16.384-4.447-110.595-64.631-255.623-26.136-257.075-25.74-8.375 2.306-17.141 1.214-24.688-3.073-7.549-4.288-12.975-11.26-15.28-19.631-2.306-8.369-1.214-17.136 3.073-24.684 4.288-7.549 11.26-12.976 19.631-15.281 1.744-.483 43.671-11.918 102.066-13.847 34.319-1.126 67.387 1.267 98.288 7.126 39.34 7.465 75.269 20.589 106.788 39.008 7.497 4.382 12.838 11.42 15.041 19.816 2.203 8.398 1.002 17.152-3.381 24.648-5.799 9.933-16.559 16.105-28.079 16.105zm-172.156-61.976c50.624 0 111.749 8.637 165.869 40.266 1.942 1.136 4.055 1.711 6.287 1.711 4.438 0 8.58-2.373 10.81-6.193 1.689-2.889 2.151-6.255 1.304-9.485-.847-3.23-2.902-5.938-5.787-7.624-29.543-17.265-63.331-29.587-100.423-36.625-29.458-5.586-61.054-7.87-93.901-6.787-56.043 1.851-95.742 12.677-97.405 13.137-3.232.89-5.913 2.976-7.562 5.879-1.649 2.903-2.068 6.276-1.181 9.497s2.974 5.903 5.876 7.551c2.903 1.65 6.276 2.069 9.498 1.182.816-.228 46.25-12.509 106.615-12.509z"/><path d="M340.592 371.449c-4.503 0-8.946-1.206-12.847-3.488-38.651-22.587-83.681-26.202-114.653-25.263-34.26 1.041-59.681 7.805-59.934 7.874-13.528 3.703-27.574-4.301-31.287-17.847-3.712-13.541 4.274-27.587 17.804-31.312 1.202-.333 30.338-8.299 70.973-9.638 23.845-.79 46.833.879 68.328 4.959 27.424 5.2 52.487 14.356 74.491 27.216 5.88 3.438 10.069 8.958 11.795 15.542 1.727 6.587.785 13.452-2.653 19.33v.001c-4.557 7.788-12.993 12.626-22.017 12.626zm-118.809-48.884c32.946 0 77.1 5.364 116.056 28.13.856.5 1.781.753 2.753.753 1.978 0 3.755-1.019 4.754-2.724.74-1.265.942-2.743.571-4.16-.372-1.418-1.275-2.608-2.542-3.349-20.027-11.704-42.948-20.059-68.126-24.833-20.053-3.806-41.567-5.362-63.94-4.62-38.275 1.26-66.031 8.849-66.307 8.926-2.927.806-4.642 3.831-3.843 6.747.799 2.915 3.822 4.636 6.739 3.838.263-.072 27.503-7.442 64.585-8.568 2.984-.088 6.089-.14 9.3-.14zM256 467.177c-5.523 0-10-4.477-10-10s4.477-10 10-10h.501c5.51 0 9.983 4.458 10 9.971.016 5.523-4.448 10.013-9.971 10.029H256zM293.191 463.746c-4.72 0-8.919-3.356-9.818-8.164-1.015-5.429 2.563-10.652 7.992-11.667 28.314-5.294 55.539-17.224 78.73-34.501 4.429-3.298 10.694-2.383 13.994 2.045 3.299 4.429 2.384 10.694-2.045 13.994-25.623 19.088-55.708 32.27-87.002 38.121-.623.116-1.241.172-1.851.172z"/>
          </svg>
        </a>
        <ul class="navbar-nav ml-auto">
        <?php if(isset($_COOKIE["type"])): ?>
          <li class="nav-item">
            <a href="/logout.php" class="nav-link">Uitloggen</a>
          </li>
          <li class="nav-item">
            <a href="/adminPanel.php" class="nav-link">Panel</a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a href="/login.php" class="nav-link">Inloggen</a>
          </li>
        <?php endif; ?>
        </ul>
      </div>
    </nav>
  </header>
  <article>
    <section class="content">
      <div class="content-row">
        <div class="content-col-side">
          <aside class="content-sidebar">
            <ul class="content-sidebar-nav">
              <li class="content-sidebar-nav__item">
                <a href="/adminPanel.php" class="content-sidebar-link">
                  <svg class="content-sidebar-link__icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 408 408"><path d="M332 121.921H184.8l-29.28-34.8c-.985-1.184-2.461-1.848-4-1.8H32.76c-18.132.132-32.76 14.868-32.76 33v214.04c.022 18.194 14.766 32.938 32.96 32.96H332c18.194-.022 32.938-14.766 32.96-32.96v-177.48c-.022-18.194-14.766-32.938-32.96-32.96z"/><path d="M375.24 79.281H228l-29.28-34.8c-.985-1.184-2.461-1.848-4-1.8H76c-16.452.027-30.364 12.181-32.6 28.48h108.28c5.678-.014 11.069 2.492 14.72 6.84l25 29.72H332c26.005.044 47.076 21.115 47.12 47.12v167.52c16.488-2.057 28.867-16.064 28.88-32.68v-177.48c-.043-18.101-14.66-32.788-32.76-32.92z"/></svg>
                  Bladeren
                </a>
              </li>
            </ul>

            <ul class="library">
              <li class="library__item-divider">
                <h6>Artists</h6>
              </li>
              <?php foreach($db_artists as $artist): ?>
              <li class="library__item">
                <a href="/adminPanel.php?artist?<?php echo($artist->name)?>" class="library__link">
                  <h5><?php echo($artist->name)?></h5>
                </a>
              </li>
              <?php endforeach; ?>
            </ul>
          </aside>
        </div>
        <div class="content-col-full">
          <div class="content-window">
          <?php if($curr_object == "album"): ?>
              <div class="album-window">
                <div class="row">
                  <div class="col-6 col-md-3">
                    <div class="album-card">
                      <img class="album__cover" src="<?php echo($db_album_from_curr->album_cover_url) ?>" alt="album-cover-1">
                    </div>
                  </div>
                  <div class="col-6  col-md-9">
                    <div class="album-information">
                      <span class="album-information__subheading">Afspeellijst</span>
                      <h1 class="album-information__heading"><?php echo($db_album_from_curr->name) ?></h1>
                      <p class="album-information__text">Van <a class="album__artist-link" href="/adminPanel.php?artist?<?php echo($db_album_from_curr->created_by->name) ?>"><strong><?php echo($db_album_from_curr->created_by->name) ?></strong></a></p>
                      <p class="album-information__text"><?php echo($db_album_from_curr->release_date. " " .count($db_songs_from_curr)) ?> nummers</p>
                      <!-- <span><a href="#" class="btn btn-primary">Afspelen</a></span> -->
                    </div>
                  </div>
                </div>
              </div>
              <form action="" method="POST">
                <div class="form-row">
                  <div class="col">
                  <select class="form-control" name="Song" id="selected_song" aria-label="song">
                      <option default>Selecteer van album</option>
                    <?php foreach($db_songs_from_curr as $song): ?>
                      <option><?php echo($song->name) ?></option>
                    <?php endforeach; ?>
                  </select>
                  </div>
                  <div class="col">
                    <input type="hidden" name="form" value="selected_song">
                    <input class="btn btn-primary" type="submit" value="Selecteren">
                  </div>
                </div>
                <div class="form-row">
                  <div class="col">
                    <button class="btn btn-danger btn-sm" type="submit" name="delete" value="delete">X</button>
                  </div>
                </div>
              </form>
              <?php if(isset($selected_song)): ?>
              <form class="track-table" action="" method="POST">
                <div class="track-thead">
                  <div class="track-head">
                    <span class="track-head__title">
                      Titel
                    </span>
                  </div>
                  <div class="track-head">
                    <span class="track-head__title">
                      Artiest
                    </span>
                  </div>
                  <div class="track-head">
                    <span class="track-head__title">
                      Album
                    </span>
                  </div>
                  <div class="track-head">
                    <span class="track-head__title">
                      Duur (in sec)
                    </span>
                  </div>
                  <div class="track-head">
                
                  </div>
                </div>
                
              <div class="track-entry">
                <div class="track-item">
                  <div class="form-group">
                    <input type="text" class="form-control" name="track_name" id="track_name" aria-describedby="username" value="<?php echo($selected_song->name) ?>" aria-label="titel">
                  </div> 
                  </div>
                  <div class="track-item">
                    <?php echo($db_album_from_curr->created_by->name) ?>
                  </div>
                  <div class="track-item">
                    <?php echo($db_album_from_curr->name) ?>
                  </div>
                  <div class="track-item">
                    <div class="form-group">
                      <input type="text" class="form-control" name="duration" id="duration" aria-label="duration" value="<?php echo($selected_song->duration) ?>">
                    </div>
                  </div>
                  <div class="track-item">
                    <input type="hidden" name="form" value="change_song">
                    <input type="hidden" name="selected_song" value="<?php echo($selected_song->name) ?>">
                    <button type="submit" value="change_song" class="btn btn-primary btn-sm">Wijzig</button>
                  </div>
                </div>
              </form>
                <?php endif; ?>
                <!-- NEW TABLE -->
                <br>
                <h3>Nieuw:</h3>
                <form class="track-table" action="" method="POST">
                  <div class="track-thead">
                    <div class="track-head">
                      <span class="track-head__title">
                        Titel
                      </span>
                    </div>
                    <div class="track-head">
                      <span class="track-head__title">
                        Artiest
                      </span>
                    </div>
                    <div class="track-head">
                      <span class="track-head__title">
                        Album
                      </span>
                    </div>
                    <div class="track-head">
                      <span class="track-head__title">
                        Duur (in sec)
                      </span>
                    </div>
                    <div class="track-head">
                  
                    </div>
                  </div>
                  <div class="track-entry">
                  <div class="track-item">
                    <div class="form-group">
                      <input type="text" class="form-control" name="track_name" id="track_name" aria-describedby="username" value="Naam" aria-label="titel">
                    </div>
                    
                  </div>
                  <div class="track-item">
                    <?php echo($db_album_from_curr->created_by->name) ?>
                  </div>
                  <div class="track-item">
                    <?php echo($db_album_from_curr->name) ?>
                  </div>
                  <div class="track-item">
                    <div class="form-group">
                      <input type="text" class="form-control" name="duration_new" id="duration_new" aria-label="duration" value="000">
                    </div>
                  </div>
                  <div class="track-item">
                    <input type="hidden" name="form" value="create_song">
                    <button type="submit" value="create_song" class="btn btn-primary btn-sm">Maak</button>
                  </div>
                </div>
                </form>
            
              </div>
              <?php elseif($curr_object == "artist"): ?>
              <div class="artist-window">
                <div class="row">
                  <div class="col-6 col-md-3">
                    <div class="artist-card">
                      <img class="artist__cover" src="<?php echo($db_artist->avatar) ?>" alt="album-cover-1">
                    </div>
                  </div>
                  <div class="col-6 col-md-9">
                    <div class="artist-information">
                      <span class="artist-information__subheading">Artiest</span>
                      <h1 class="artist-information__heading"><?php echo($db_artist->name) ?></h1>
                      <p class="artist-information__text"><?php echo ($db_artist->bio) ?></p>
                      <p class="artist-information__text"><?php echo($db_artist->age) ?> years old.</p>
                    </div>
                  </div>
                </div>
              </div>
              <hr>
              <h3>Nieuwste release</h3>
              <?php foreach($db_albums_from_artist as $album): ?>
              <?php $songs_from_album = $dao->get_songs_from_album($album->name);?>
              <div class="album-window">
                <div class="row">
                  <div class="col-6 col-md-3">
                    <a href="/adminPanel.php?album?<?php echo($album->name) ?>"  class="album-card">
                      <img class="album__cover" src="<?php echo($album->album_cover_url) ?>" alt="album-cover-1">
                    </a>
                  </div>
                  <div class="col-6 col-md-9">
                    <div class="album-information">
                      <span class="album-information__subheading">Afspeellijst</span>
                      <h1 class="album-information__heading"><a href="/adminPanel.php?album?<?php echo($album->name) ?>"><?php echo($album->name) ?></a></h1>
                      <p class="album-information__text"><?php echo($album->release_date. " " .count($songs_from_album)) ?> nummers</p>
                      <!-- <span><a href="#" class="btn btn-primary">Afspelen</a></span> -->
                    </div>
                  </div>
                </div>
              </div>
              
            <?php endforeach; ?>
            <?php else: ?>
              <h1 class="content__title">Browse</h1>

            <h4>Albums</h4>
            <hr>
            <div class="browse-collection">
              <div class="row">
                <?php foreach($db_albums_rand as $album): ?>
                  <div class="col-sm-6 col-md-4 col-lg-3">
                    <a href="/adminPanel.php?album?<?php echo($album->name) ?>" class="album-card">
                      <img class="album__cover" src="<?php echo($album->album_cover_url) ?>" alt="album-cover-1">
                    </a>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
            <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </section>
  </article>

  <footer class="footer">
    <div class="footer-info">
      <a href="./" class="footer__link">
        <svg class="footer__logo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
          <circle class="fill" cx="256" cy="256" r="246" fill=""></circle>
          <g fill="#000">
            <path d="M382.775 293.97c-3.413 5.834-9.553 9.092-15.858 9.092-3.145 0-6.33-.812-9.242-2.508-48.208-28.177-103.906-32.702-142.147-31.544-42.364 1.292-73.426 9.656-73.736 9.738-9.765 2.664-19.855-3.077-22.535-12.842-2.678-9.765 3.052-19.85 12.811-22.54 1.397-.383 34.828-9.464 81.314-10.995 27.386-.906 53.742 1.004 78.34 5.668 31.157 5.912 59.571 16.287 84.469 30.835 8.746 5.111 11.694 16.345 6.584 25.096zm17.698-54.378c-3.858 0-7.763-.988-11.332-3.077-114.616-66.982-263.294-27.164-264.784-26.749-11.984 3.299-24.37-3.735-27.669-15.719-3.301-11.982 3.734-24.37 15.718-27.669 1.717-.475 42.72-11.61 99.741-13.493 33.592-1.107 65.922 1.235 96.095 6.956 38.21 7.25 73.069 19.973 103.605 37.817 10.731 6.273 14.347 20.052 8.073 30.784-4.179 7.157-11.709 11.15-19.447 11.15zM353.978 353.773c-2.881 4.924-8.063 7.675-13.386 7.675-2.652 0-5.342-.683-7.799-2.12-40.703-23.786-87.722-27.609-120.006-26.626-35.765 1.086-61.986 8.145-62.25 8.218-8.243 2.255-16.762-2.596-19.022-10.84-2.26-8.245 2.571-16.758 10.815-19.028 1.18-.327 29.403-7.992 68.648-9.284 23.113-.765 45.369.847 66.134 4.789 26.299 4.986 50.293 13.742 71.311 26.025 7.386 4.321 9.874 13.806 5.555 21.191z"></path></g><path d="M256 512c-68.38 0-132.667-26.628-181.019-74.981C26.628 388.667 0 324.38 0 256c0-51.13 15.028-100.49 43.461-142.745 3.083-4.582 9.297-5.797 13.879-2.714s5.797 9.297 2.714 13.879C33.851 163.363 20 208.862 20 256c0 130.131 105.869 236 236 236s236-105.869 236-236S386.131 20 256 20c-50.354 0-98.401 15.636-138.946 45.217-4.462 3.255-10.718 2.277-13.973-2.185s-2.277-10.717 2.185-13.973C149.257 16.965 201.38 0 256 0c68.38 0 132.667 26.628 181.019 74.981C485.372 123.333 512 187.62 512 256s-26.628 132.667-74.981 181.019C388.667 485.372 324.38 512 256 512z"></path><path d="M76.346 97.911c-2.434 0-4.872-.878-6.789-2.661-4.044-3.761-4.307-10.054-.545-14.098l.208-.222c3.802-4.005 10.132-4.169 14.138-.366 4.005 3.803 4.169 10.132.366 14.138-1.979 2.127-4.677 3.209-7.378 3.209zM366.917 313.062c-4.997 0-9.933-1.337-14.275-3.867-46.16-26.979-99.869-31.313-136.811-30.188-40.402 1.232-70.2 9.068-71.449 9.401-15.02 4.099-30.638-4.803-34.768-19.854-4.13-15.056 4.75-30.68 19.797-34.827 3.67-1.005 37.044-9.814 83.643-11.349 28.108-.929 55.204 1.035 80.532 5.838 32.271 6.123 61.76 16.898 87.65 32.025 13.491 7.881 18.056 25.274 10.175 38.771-.001.002-.003.005-.005.008-5.068 8.661-14.451 14.042-24.489 14.042zM226.148 258.85c38.798 0 90.778 6.304 136.574 33.071 1.295.755 2.711 1.141 4.195 1.141 2.961 0 5.729-1.585 7.225-4.138 2.318-3.975.975-9.094-2.995-11.414-23.917-13.975-51.266-23.948-81.289-29.645-23.884-4.529-49.506-6.381-76.145-5.499-45.075 1.484-77.641 10.272-79.005 10.646-4.41 1.216-7.023 5.816-5.807 10.25 1.216 4.431 5.818 7.054 10.259 5.84 1.408-.376 33.375-8.784 76.063-10.086 3.504-.107 7.155-.166 10.925-.166zm174.326-9.258c-5.748 0-11.414-1.538-16.384-4.447-110.595-64.631-255.623-26.136-257.075-25.74-8.375 2.306-17.141 1.214-24.688-3.073-7.549-4.288-12.975-11.26-15.28-19.631-2.306-8.369-1.214-17.136 3.073-24.684 4.288-7.549 11.26-12.976 19.631-15.281 1.744-.483 43.671-11.918 102.066-13.847 34.319-1.126 67.387 1.267 98.288 7.126 39.34 7.465 75.269 20.589 106.788 39.008 7.497 4.382 12.838 11.42 15.041 19.816 2.203 8.398 1.002 17.152-3.381 24.648-5.799 9.933-16.559 16.105-28.079 16.105zm-172.156-61.976c50.624 0 111.749 8.637 165.869 40.266 1.942 1.136 4.055 1.711 6.287 1.711 4.438 0 8.58-2.373 10.81-6.193 1.689-2.889 2.151-6.255 1.304-9.485-.847-3.23-2.902-5.938-5.787-7.624-29.543-17.265-63.331-29.587-100.423-36.625-29.458-5.586-61.054-7.87-93.901-6.787-56.043 1.851-95.742 12.677-97.405 13.137-3.232.89-5.913 2.976-7.562 5.879-1.649 2.903-2.068 6.276-1.181 9.497s2.974 5.903 5.876 7.551c2.903 1.65 6.276 2.069 9.498 1.182.816-.228 46.25-12.509 106.615-12.509z"></path><path d="M340.592 371.449c-4.503 0-8.946-1.206-12.847-3.488-38.651-22.587-83.681-26.202-114.653-25.263-34.26 1.041-59.681 7.805-59.934 7.874-13.528 3.703-27.574-4.301-31.287-17.847-3.712-13.541 4.274-27.587 17.804-31.312 1.202-.333 30.338-8.299 70.973-9.638 23.845-.79 46.833.879 68.328 4.959 27.424 5.2 52.487 14.356 74.491 27.216 5.88 3.438 10.069 8.958 11.795 15.542 1.727 6.587.785 13.452-2.653 19.33v.001c-4.557 7.788-12.993 12.626-22.017 12.626zm-118.809-48.884c32.946 0 77.1 5.364 116.056 28.13.856.5 1.781.753 2.753.753 1.978 0 3.755-1.019 4.754-2.724.74-1.265.942-2.743.571-4.16-.372-1.418-1.275-2.608-2.542-3.349-20.027-11.704-42.948-20.059-68.126-24.833-20.053-3.806-41.567-5.362-63.94-4.62-38.275 1.26-66.031 8.849-66.307 8.926-2.927.806-4.642 3.831-3.843 6.747.799 2.915 3.822 4.636 6.739 3.838.263-.072 27.503-7.442 64.585-8.568 2.984-.088 6.089-.14 9.3-.14zM256 467.177c-5.523 0-10-4.477-10-10s4.477-10 10-10h.501c5.51 0 9.983 4.458 10 9.971.016 5.523-4.448 10.013-9.971 10.029H256zM293.191 463.746c-4.72 0-8.919-3.356-9.818-8.164-1.015-5.429 2.563-10.652 7.992-11.667 28.314-5.294 55.539-17.224 78.73-34.501 4.429-3.298 10.694-2.383 13.994 2.045 3.299 4.429 2.384 10.694-2.045 13.994-25.623 19.088-55.708 32.27-87.002 38.121-.623.116-1.241.172-1.851.172z"></path>
          </svg>
      </a>
      
      <h3 class="h4 footer-info__title">Music Player</h3>

      <span class="copy">Music Player Inc &copy; 2020</span>
    </div>
    <div style="opacity: 0.2;">Icons made by <a href="http://www.freepik.com/" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></div>
  </footer>
</body>
</html>
