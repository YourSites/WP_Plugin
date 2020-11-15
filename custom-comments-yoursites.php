<?php
/* 
Plugin Name: custom-comments-yoursites.php
Beschreibung: Plugin, welches die Comment Funktion von Wordpress mit Bewertungen erweitert
Author: Merdan Karatas
Version: alpha 0.2.2
Datum: 05.11.2020
*/

/*TODO: Datenbank Tabelle erstellen zum testen der Insertierung
URL Field bei Neuerstellung; 
*/

//implementiert eine Datenbank Verbindung

//Hinzufuegen von Aktionen bei Wordpress Funktionen genannt als erstes Argument 
add_action( 'comment_form_logged_in_after', 'yoursites_rating_field' );
add_action( 'comment_form_after_fields', 'yoursites_rating_field' );
add_action( 'wp_enqueue_scripts', 'yoursites_rating_styles' );
add_action('comment_post', 'save_review_value_in_db', 10, 1);

//rating funktion (Benoetigt noch ueberarbeitung des 0 sterne inputs)
function yoursites_rating_field(){

    ?>
    <!-- This is a comment -->
    <label for="rating">Rating<span class="required">*</span></label>  <!-- Text vor dem Rating -->
    <fieldset class="ratingCSS">
		<span class="rating-containerCSS">
			<?php for ( $ratingCount = 5; $ratingCount >= 1; $ratingCount-- ) : ?> <!-- Schleife mit Bewertungsziffern -->
                <?php $ratingNumber = esc_attr( $ratingCount );?> <!-- $ratingNumber wird gesetzt und Attribut encoded -->

				<input type="radio" id="rating-<?php echo $ratingNumber ?>"
                    name="rating" value="<?php echo $ratingNumber ?>"/> <!-- Input wird erstellt mit typ radio -->
                    <label for="rating-<?php echo $ratingNumber ?>"><?php echo esc_html( $ratingCount );?></label>
			<?php endfor; ?>
			<input type="radio" id="rating-0" class="star-clearCSS" name="rating" value="0" />  <!-- wird erstellt mit typ radio fuer 0 Sterne Bewertung -->
            <label for="rating-0">0</label> 
		</span>
	</fieldset> 
<?php
}

//Funktion zur Speicherung des Review-Werts
function save_review_value_in_db($id_post) {
	if ( ( isset( $_POST['rating'] ) ) && ( '' !== $_POST['rating'] ) )
	    $rating = intval( $_POST['rating'] );
	    add_comment_meta( $comment_id, 'rating', $rating );

        require 'connect-to-database.php'; //
        
        $currentposttitle = get_the_title($post = $id_post);
        
        $stmt = $databaseConnection->prepare("INSERT INTO reviews (Adresse, Bewertung) VALUES (?, ?)");
        $stmt->bind_param("si", $currentposttitle, $rating);
		$stmt->execute();
}

//Style.css Implementierungsfunktion (enqueue)
function yoursites_rating_styles() {

	wp_register_style( 'yoursites_rating_styles', plugins_url( '/', __FILE__ ) . 'assets/style.css' ); 
	wp_enqueue_style( 'dashicons' );
	wp_enqueue_style( 'yoursites_rating_styles' );
}


function getTitleOfPost() {
    $currentposttitle = utf8_encode(get_post( $post )->post_title);
    return $currentposttitle;
}



?>
