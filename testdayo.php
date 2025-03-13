<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>女優専用ページ テスト</title>
  <style>
    /* --- 全体コンテナ --- */
    .container {
        margin-top: 0.3rem;
        margin-bottom: 5rem;
        background-color: #f4f4f4;
    }
    /* --- 名前・プロフィール部分 --- */
    .name-container {
        font-size: 1.6rem;
        font-weight: bold;
        text-align: center;
        color: #333;
        padding-top: 1rem;
        padding-bottom: 1rem;
        font-family: sans-serif;
        background-color: #FFEEFF;
        margin: 0 auto; 
    }
    .name-container h1 {
        font-size: 1.6rem;
        margin: 0.2rem;
        margin-bottom: 0.1rem;
    }
    .name-container p {
        font-size: 1.6rem;
        margin: 0.2rem;
        margin-bottom: 0.1rem;
    }
    /* --- 写真セクション（元の仕様：最大70%・中央配置） --- */
    .photo-section {
        max-width: 70%;
        margin: 0.3rem auto;
    }
    .photo-section img {
        width: 100%;
        height: auto;
        border-radius: 2px;
        aspect-ratio: 5 / 7; /* アスペクト比5:7 */
    }
    
    /* -------------------------
       カルーセル用 CSS（ネットのコードを元に、以下のように修正）
       【変更点】
       1. .contains と .slide のサイズを固定サイズから width:100% と aspect-ratio:5/7 に変更（元の仕様に合わせる）
       2. .scroll_prev, .scroll_next の左右位置を px から割合（5%）に変更
       3. .move_controler の bottom を 5% に変更
    -------------------------- */
    .carousel {
      display: flex;
      justify-content: center;
    }
    .contains {
      width: 100%;
      aspect-ratio: 5 / 7;  
      overflow: hidden;
      position: relative;
      padding: 0;
    }
    .slide_select {
      display: none;
    }
    .slide {
      width: 100%;
      aspect-ratio: 5 / 7;
      position: absolute;
      opacity: 0;
    }
    .scroll_button {
      position: absolute;
      display: block;
      height: 30px;
      width: 30px;
      top: 50%;
      margin-top: -20px;
      border-width: 5px 5px 0 0;
      border-style: solid;
      border-color: #fdfdfd;
      cursor: pointer;
      opacity: 0.5;
      z-index: 3;
    }
    .scroll_button:hover {
      opacity: 1;
    }
    .scroll_prev {
      left: 5%;
      transform: rotate(-135deg);
    }
    .scroll_next {
      right: 5%;
      transform: rotate(45deg);
    }
    .move_controler {
      position: absolute;
      bottom: 5%;
      width: 100%;
      text-align: center;
    }
    .button_move {
      display: inline-block;
      height: 15px;
      width: 15px;
      margin: 0 2px;
      border-radius: 100%;
      cursor: pointer;
      opacity: 0.5;
      z-index: 2;
      background-color: #fdfdfd;
    }
    .button_move:hover {
      opacity: 0.75;
    }
  </style>
</head>
<body>
  <!-- ヘッダー（簡易版） -->
  <header>
    <h2 style="text-align:center;">テスト用ヘッダー</h2>
  </header>
  
  <!-- メインコンテンツ -->
  <div class="container">
    <div class="name-container">
      <!-- 名前部分（ダミーデータ） -->
      <h1>
      <?php
      // ダミーデータ（通常は WordPress の get_post_meta で取得）
      $japanese = "梨音";
      $roman    = "RION";
      if ( ! empty( $japanese ) && ! empty( $roman ) ) {
          echo esc_html( $japanese ) . ' / ' . esc_html( $roman );
      } elseif ( ! empty( $japanese ) ) {
          echo esc_html( $japanese );
      } elseif ( ! empty( $roman ) ) {
          echo esc_html( $roman );
      }
      ?>
      </h1>
      <?php
      // 改名データ（ダミーデータ）
      $reign_romans   = array("OldRION");
      $reign_japaneses = array("旧梨音");
      $max = max( count($reign_romans), count($reign_japaneses) );
      if ( $max > 0 ) {
          for ( $i = 0; $i < $max; $i++ ) {
              $roman_val    = isset( $reign_romans[$i] ) ? $reign_romans[$i] : '';
              $japanese_val = isset( $reign_japaneses[$i] ) ? $reign_japaneses[$i] : '';
              if ( empty( $roman_val ) && empty( $japanese_val ) ) {
                  continue;
              }
              echo '<p>';
              if ( ! empty( $japanese_val ) ) {
                  echo esc_html( $japanese_val );
              }
              if ( ! empty( $japanese_val ) && ! empty( $roman_val ) ) {
                  echo ' / ';
              }
              if ( ! empty( $roman_val ) ) {
                  echo esc_html( $roman_val );
              }
              echo '</p>';
          }
      }
      ?>
    </div>
    
    <!-- カルーセル写真セクション -->
    <div class="photo-section">
      <?php
      // ダミーデータ：画像の URL 配列（actress_gallery）
      $gallery = array(
        "https://picsum.photos/400/560?random=1",
        "https://picsum.photos/400/560?random=2",
        "https://picsum.photos/400/560?random=3"
      );
      if ( ! empty( $gallery ) && is_array( $gallery ) ) {
          $count = count( $gallery );
          
          // --- 動的に CSS ルールを生成して出力 ---
          echo '<style>';
          for ( $i = 1; $i <= $count; $i++ ) {
              echo '.slide_select:nth-of-type(' . $i . '):checked ~ .slide:nth-of-type(' . $i . ') { opacity: 1; }';
              echo '.slide_select:nth-of-type(' . $i . '):checked ~ .move_controler .button_move:nth-of-type(' . $i . ') { opacity: 1; }';
          }
          echo '</style>';
          
          // --- ラジオボタンの出力 ---
          for ( $i = 0; $i < $count; $i++ ) {
              $checked = ( $i === 0 ) ? 'checked' : '';
              echo '<input class="slide_select" type="radio" id="Slide' . $i . '" name="slide_check" ' . $checked . ' />';
          }
          
          // --- スライドの出力 ---
          echo '<div class="carousel"><div class="contains">';
          for ( $i = 0; $i < $count; $i++ ) {
              $prev_index = ( $i === 0 ) ? $count - 1 : $i - 1;
              $next_index = ( $i === $count - 1 ) ? 0 : $i + 1;
              echo '<div class="slide">';
                  echo '<div class="scroll_controler">';
                      echo '<label class="scroll_button scroll_prev" for="Slide' . $prev_index . '"></label>';
                      echo '<label class="scroll_button scroll_next" for="Slide' . $next_index . '"></label>';
                  echo '</div>';
                  echo '<img src="' . esc_url( $gallery[$i] ) . '">';
              echo '</div>';
          }
          
          // --- 移動用ボタンの出力 ---
          echo '<div class="move_controler">';
          for ( $i = 0; $i < $count; $i++ ) {
              echo '<label class="button_move" for="Slide' . $i . '"></label>';
          }
          echo '</div>';
          echo '</div></div>'; // .contains, .carousel
      }
      ?>
    </div>
  </div>
  
  <!-- フッター（簡易版） -->
  <footer>
    <p style="text-align:center;">テスト用フッター</p>
  </footer>
</body>
</html>
