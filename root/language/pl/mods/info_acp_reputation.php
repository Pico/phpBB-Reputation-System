<?php
/**
*
* @package	Reputation System
* @author	Pico88 (https://github.com/Pico88)
* @copyright (c) 2012
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'REPUTATION_SYSTEM'				=> 'System Reputacji',

	'ACP_REPUTATION_SYSTEM'				=> 'System Reputacji',
	'ACP_REPUTATION_SYSTEM_EXPLAIN'		=> 'Tutaj możesz zarządzać całym Systemem Reputacji.<br />Menu po lewej pomoże Ci poruszać się między działami.',
	'ACP_REPUTATION_OVERVIEW'			=> 'Informacje',
	'ACP_REPUTATION_SETTINGS'			=> 'Ustawienia reputacji',
	'ACP_REPUTATION_SETTINGS_EXPLAIN'	=> 'Tutaj można skonfigurować System Reputacji do własnych potrzeb.',
	'ACP_REPUTATION_GIVE'				=> 'Przyznaj punkt',
	'ACP_REPUTATION_RANKS'				=> 'Rangi',
	'ACP_REPUTATION_BANS'				=> 'Bany',
	'MCP_REPUTATION'					=> 'Reputacja',
	'MCP_REPUTATION_FRONT'				=> 'Przegląd',
	'MCP_REPUTATION_LIST'				=> 'Lista',
	'MCP_REPUTATION_GIVE'				=> 'Przyznaj punkt',
	'UCP_REPUTATION'					=> 'Reputacja',
	'UCP_REPUTATION_FRONT'				=> 'Przegląd',
	'UCP_REPUTATION_LIST'				=> 'Lista otrzymanych punktów',
	'UCP_REPUTATION_GIVEN'				=> 'Lista udzielonych punktów',
	'UCP_REPUTATION_SETTING'			=> 'Ustawienia',

	'ACP_RS_MAIN'			=> 'Główne',
	'ACP_RS_DISPLAY'		=> 'Wyświetlanie',
	'ACP_RS_POSTS_RATING'	=> 'Oceniania postów',
	'ACP_RS_USERS_RATING'	=> 'Oceniania użytkowników',
	'ACP_RS_COMMENT'		=> 'Komentarze',
	'ACP_RS_POWER'			=> 'Siła reputacji',
	'ACP_RS_RANKS'			=> 'Rangi',
	'ACP_RS_TOPLIST'		=> 'Toplist',
	'ACP_RS_BAN'		 	=> 'Bany',

	'RS_ENABLE'		=> 'Włącz System Reputacji',

	'RS_NEGATIVE_POINT'				=> 'Włącz negatywne punkty',
	'RS_NEGATIVE_POINT_EXPLAIN'		=> 'Kiedy wyłączysz, nie będzie możliwości przyznawania negatywnych punktów. Reputacji będzie zbliżona do "Lubię to!" z FB.',
	'RS_MIN_REP_NEGATIVE'			=> 'Minimalna reputacja dla negatywnych punktów',
	'RS_MIN_REP_NEGATIVE_EXPLAIN'	=> 'Jaką reputację musi posiadać użytkownik, aby móc przyznawać negatywne punkty reputacji. Ustawienie wartość 0 wyłącza tę funckję.',
	'RS_WARNING'					=> 'Włącz ostrzeżenia',
	'RS_WARNING_EXPLAIN'			=> 'Użytkownik ze stosownymi uprawnieniami może przyznawać negatywne punkty podczas wystawiania ostrzeżenia.',
	'RS_NOTIFICATION'				=> 'Włącz powiadomienia',
	'RS_NOTIFICATION_EXPLAIN'		=> 'Opcja ta włączy informację o nowych punktach reputacji w nagłówku forum.',
	'RS_PM_NOTIFY'					=> 'Włącz powiadomienia PW',
	'RS_PM_NOTIFY_EXPLAIN'			=> 'Opcja ta umożliwia użytkownikowi wysłanie powiadomienia PW z powiadomieniem o punktach reputacji.',
	'RS_MIN_POINT'					=> 'Minimum punktów',
	'RS_MIN_POINT_EXPLAIN'			=> 'Minimalna liczba punktów, które użytkownik może otrzymać. Ustawienie wartość 0 wyłącza tę funckję.',
	'RS_MAX_POINT'					=> 'Maksimum punktów',
	'RS_MAX_POINT_EXPLAIN'			=> 'Maksymalna liczba punktów, które użytkownik może otrzymać. Ustawienie wartość 0 wyłącza tę funckję.',

	'RS_PER_PAGE'							=> 'Wpisów reputacji na stronę',
	'RS_PER_PAGE_EXPLAIN'					=> 'Ile wpisów ma być wyświetlanych na stronach z reputacją',
	'RS_DISPLAY_AVATAR'						=> 'Wyświetl awatary',
	'RS_SORT_MEMBERLIST_BY_REPO'			=> 'Sortuj listę użytkowników na podstawie reputacji',
	'RS_SORT_MEMBERLIST_BY_REPO_EXPLAIN'	=> 'Zmienia domyślny sposób sortowania listy użytkowników uwzględniając reputację a nie nazwę użytkownika.',
	'RS_POINT_TYPE'							=> 'Metoda wyświeltania punktów',
	'RS_POINT_TYPE_EXPLAIN'					=> 'Tutaj możesz ustawić metodę wyświetlanai punktów',
	'RS_POINT_VALUE'						=> 'Wartość liczbowa',
	'RS_POINT_IMG'							=> 'Obrazek',

	'RS_POST_RATING'				=> 'Włącz ocenę postu',
	'RS_ALLOW_REPUTATION_BUTTON'	=> 'Wyślij i włącz reputację na wszystkich forach',
	'RS_HIGHLIGHT_POST'				=> 'Wyróżnij post',
	'RS_HIGHLIGHT_POST_EXPLAIN'		=> 'Post, którego reputacja jest wyższa niż wartość podana obok, zostanie wyróżniony. Ustawienie wartość 0 wyłącza tę funckję.<br /><em>Uwaga:</em> Możesz zmienić efekt wyróżnienia przez edcyję klasy <strong>highlight</strong> w pliku reputation.css.',
	'RS_HIDE_POST'					=> 'Ukryj treść postu',
	'RS_HIDE_POST_EXPLAIN'			=> 'Post, którego reputacja jest niższa niż wartość podana obok, zostanie ukryty. Ustawienie wartość 0 wyłącza tę funckję.',
	'RS_ANTISPAM'					=> 'Antyspam',
	'RS_ANTISPAM_EXPLAIN'			=> 'Opcja ta uniemożliwia ocenianie postów dla użytkowników, którzy ocenili za dużą liczbę postów w ciągu ostatnich godzin. Ustwienie wartość 0 dla jednego lub obu pół wyłącza tą funkcję.',
	'RS_POSTS'						=> 'Ilość ocenionych postów',
	'RS_HOURS'						=> 'w ciągu ostatnich godzin',
	'RS_ANTISPAM_METHOD'			=> 'Metoda sprawdzania antyspam',
	'RS_ANTISPAM_METHOD_EXPLAIN'	=> 'Metoda wszyscy użytkownic uwzględnia wszystkie ocenione posty, nie zależnie od tego kto był jego autorem. Metoda ten sam użytownik sprawdza tylko punkty przyznane temu samemu użytownikowi (tzn. użytownik może oceniać posty do woli, ale nie może ocenić postów tego samo użytkownika ponad limit ustawiony powyżej).',
	'RS_SAME_USER'					=> 'Ten sam użytkownik',
	'RS_ALL_USERS'					=> 'Wszyscy użytkownicy',

	'RS_USER_RATING'				=> 'Włącz ocenę użytkownika',
	'RS_USER_RATING_GAP'			=> 'Przerwa w ocenianiu',
	'RS_USER_RATING_GAP_EXPLAIN'	=> 'Okres czasu, w którym użytkownik nie może ocenić tego samego użytkownika. Użytkownicy muszą czekać przez podany okres, aby oddać kolejny głos. Ustawienie wartości na 0 zablokuje tą funkcję i użytkownicy będą mogli ocenić tego samego użytkownika tylko raz.',

	'RS_ENABLE_COMMENT'				=> 'Włącz komentarze',
	'RS_ENABLE_COMMENT_EXPLAIN'		=> 'Jeżeli włączone, użytkownicy będa mogli dodać własny komentarz do przyznawanego punktu.',
	'RS_FORCE_COMMENT'				=> 'Zmuś użytkownika do wpisania komentarza',
	'RS_FORCE_COMMENT_EXPLAIN'		=> 'Komentarze mogą być wymagane podczas oceny postów i użytkowników, albo dla każdej z osobna.',
	'RS_COMMENT_NO'					=> 'Nie',
	'RS_COMMENT_BOTH'				=> 'Oba (posty i użytkownicy)',
	'RS_COMMENT_POST'				=> 'Tylko ocena postu',
	'RS_COMMENT_USER'				=> 'Tylko ocena użytkownika',
	'RS_COMMEN_LENGTH'				=> 'Długość komentarza',
	'RS_COMMEN_LENGTH_EXPLAIN'		=> 'Ilość znaków jaką może zawierać komentarz.',

	'RS_ENABLE_POWER'				=> 'Włącz siłę reputacji',
	'RS_ENABLE_POWER_EXPLAIN'		=> 'Siła reputacja jest czymś, co użytkownicy forum mogą gromadzić i wykorzystywać do głosowania. Nowi użytkownicy mają niską siłę reputacji, starzy i pomocni użytkownicy mogą uzyskują większą siłę reputacji. Im większa jest twoja siła, tym więcej możesz głosować w określonym czasie oraz twój wpływ na ocenę postu czy użytkowników jest większy.<br/>Użytkownicy mogą wybrać podczas przyznawania punktów, ile punktów chcą przyznać za danych posty czy też konkretnemu użytkownikowi.',
	'RS_POWER_RENEWAL'				=> 'Czas odnowienia siły',
	'RS_POWER_RENEWAL_EXPLAIN'		=> 'Opcja ta umożliwa kontrolę przyznawania punktów.<br />Każdy punkt siły reputacji zostanie odnowiony po upływie podanego okresu.<br />Sugerowanie ustawienia: 5 godzin.<br />Ustwienie wartość 0 wyłącza tą funkcję.',
	'RS_MIN_POWER'					=> 'Minimalna siła reptuacji',
	'RS_MIN_POWER_EXPLAIN'			=> 'Jest to siła reputacji z jaką będą startowali użytkownicy forum.<br/>Dozwolone: 0-10. Sugerowane: 1.',
	'RS_MAX_POWER'					=> 'Maksymalna siła reputacji',
	'RS_MAX_POWER_EXPLAIN'			=> 'Maksymalna dozwolona siła punktów reputacji.<br />Użytkownik może wybrać punkty z rozwijanego menu.<br />Dozwolone: 1-20. Sugerowane: 3.',
	'RS_MAX_POWER_WARNING'			=> 'Maksymalna siła reputacji dla ostrzeżeń',
	'RS_MAX_POWER_WARNING_EXPLAIN'	=> 'Maksymalna dozwolona siła punktów reputacji dla wystawianych ostrzeżeń.',
	'RS_MAX_POWER_BAN'				=> 'Maksymalna siła reputacji dla banów',
	'RS_MAX_POWER_BAN_EXPLAIN'		=> 'Maksymalna siła punktów reputacji, jakie użytkownik otrzyma w przypadku otrzymania banu trwającego 1 miesiąc lub dłużej (w tym ban na stałe).<br />Jeżeli użytkownik został zbanowany na krótszy okres, otrzyma stosunkową liczbę punktów do okresu bana.',
	'RS_POWER_EXPLAIN'				=> 'Wyjaśnienie siły reputacji',
	'RS_POWER_EXPLAIN_EXPLAIN'		=> 'Wyjaśnij użytkownikom jak jest obliczana siła reputacji.',
	'RS_TOTAL_POSTS'				=> 'Czynnik postów',
	'RS_TOTAL_POSTS_EXPLAIN'		=> 'Użytkownik będzie otrzymywał większą siłę reputacji co każde x postów.',
	'RS_MEMBERSHIP_DAYS'			=> 'Czynnik dni członkostwa',
	'RS_MEMBERSHIP_DAYS_EXPLAIN'	=> 'Użytkownik będzie otrzymywał większą siłę reputacji co każde x dni.',
	'RS_POWER_REP_POINT'			=> 'Czynnik punktów reputacji',
	'RS_POWER_REP_POINT_EXPLAIN'	=> 'Użytkownik będzie otrzymywał większą siłę reputacji co każde x pkt. reputacji.',
	'RS_LOSE_POWER_BAN'				=> 'Czynnik banów',
	'RS_LOSE_POWER_BAN_EXPLAIN'		=> 'Każdy ban w przeciągu ostatniego roku będzie zmiejszał siłę reputacji o x pkt.<br />Wymaga ustawienia <em>maksymalnej siły reputacji dla banów</em>.',
	'RS_LOSE_POWER_WARN'			=> 'Czynnik ostrzeżeń',
	'RS_LOSE_POWER_WARN_EXPLAIN'	=> 'Każde ostrzeżęnie będzie zmniejszało siłę reputacji o x pkt.',
	'RS_GROUP_POWER'				=> 'Siła reputacji grupy',

	'RS_RANKS_ENABLE'				=> 'Włącz rangi',
	'RS_RANKS_PATH'					=> 'Ścieżka do obrazków rang:',
	'RS_RANKS_PATH_EXPLAIN'			=> 'Ścieżka do katalogu znajdującym się w katalogu głównym phpBB, w którym będą przechowywane obrazki rang, np. <samp>images/reputation</samp>.',

	'RS_ENABLE_TOPLIST'				=> 'Włącz Toplistę',
	'RS_ENABLE_TOPLIST_EXPLAIN' 	=> 'Na stronie głównej zostanie wyświetlona lista z użytkownikami mającymi najwięcej punktów reputacji.',
	'RS_TOPLIST_DIRECTION'			=> 'Kierunek listy',
	'RS_TOPLIST_DIRECTION_EXPLAIN'	=> 'Poziomy albo pionowy kierunek wyświetlania użytkowników na liście.',
	'RS_TL_HORIZONTAL'				=> 'Poziomy',
	'RS_TL_VERTICAL'				=> 'Pionowy',
	'RS_TOPLIST_NUM'				=> 'Użytkowników na Topliście',
	'RS_TOPLIST_NUM_EXPLAIN'		=> 'Liczba użytkowników wyświetlanych na topliście',

	'RS_ENABLE_BAN'				=> 'Włącz bany',
	'RS_ENABLE_BAN_EXPLAIN'		=> 'Opcja ta, umożliwi na automatyczne banowanie użytkowników z niską reputacją.',
	'RS_BAN_SHIELD'				=> 'Ochrona dla zbanowancyh',
	'RS_BAN_SHIELD_EXPLAIN'		=> 'Opcja ta chroni uprzednio, automatycznie zbanowanych użytkowników przed kolejnym banem za niską reputację. Taki użytkownik nie będzie mógł być ponownie zbanowany w ustanowionym okresie czasu od wygaśniecia bana.<br />Ustwienie wartość 0 wyłącza tę funkcję.',
	'RS_BAN_GROUPS'				=> 'Wykluczone grupy',
	'RS_BAN_GROUPS_EXPLAIN'		=> 'Jeśli nie ma wybranych grup, wszyscy użytkownicy mogą być zbanowani (z wyjątkiem założycieli). W celu zaznaczenia (lub odznaczenia) więcej niż jednej grupy, musisz użyć kombinacji CTRL+LPM (albo CMD-LPM dla Mac) na grupie. Jeżeli zapomnisz przytrzymać CTRL/CMD na wybranej grupie, wszystkie wcześniej wybrane pozycje zostaną odznaczone',

	'RS_SYNC'						=> 'Synchronizacja Systemu Reputacji',
	'RS_SYNC_EXPLAIN'				=> 'Możesz zsynchronizować System Reputacji po: masowym usunięciu postów/tematów/użytkowników, zmianie ustawień reputacji, zmianie autorów postów, konwersji z innych systemów. Synchronizacja zajmie chwilę, więc proszę być cierpliwym. Po zakończeniu synchronizacji zostaniesz powiadomiony o tym fakcie.<br /><strong>Uwaga!</strong> Podczas synchronizacji zostaną usunięte punkty reputacji, które nie są zgodne z ustawieniami reputacji. Zaleca się wykonanie kopii zapasowej tabeli reputacji przed synchronizacją.',
	'RS_SYNC_STEP_DEL'				=> 'Krok 1/7 - usunięcie punktów reputacji nieistniejących użytkowników',
	'RS_SYNC_STEP_POSTS_DEL'		=> 'Krok 2/7 - usunięcie pnktów reputacji skasowanych postów',
	'RS_SYNC_STEP_REPS_DEL'			=> 'Krok 3/7 - usunięcie punktów reputacji niezgodnych z ustawieniami reputacji',
	'RS_SYNC_STEP_POST_AUTHOR'		=> 'Krok 4/7 - sprawdzanie autorów postów i synchronizacji punktów reputacji uwzględniając nowych autorów postów',
	'RS_SYNC_STEP_FORUM'			=> 'Krok 5/7 - sprawdzanie ustawień for i synchronizacja punktów reputacji zgodnie z ustawieniami forów',
	'RS_SYNC_STEP_USER'				=> 'Krok 6/7 - synchronizacja punktów reputacji użytkowników',
	'RS_SYNC_STEP_POSTS'			=> 'Krok 7/7 - synchronizacja punktów reputacji postów',
	'RS_SYNC_DONE'					=> 'Synchronizacja Systemu Reputacji została zakończona pomyślnie',
	'RS_RESYNC_REPUTATION_CONFIRM'	=> 'Czy na pewno chcesz przeprowadzić synchronizację reputacji?',

	'RS_TRUNCATE'				=> 'Wyczyść reputację',
	'RS_TRUNCATE_EXPLAIN'		=> 'Ta procedura wyszyści tabelę z reputacją.<br /><strong>Czynności tej nie można odwrócić!</strong>',
	'RS_TRUNCATE_CONFIRM'		=> 'Czy jesteś pewien, że chcesz wyczyścić reputację?',
	'RS_TRUNCATE_DONE'			=> 'Reputacja została wyczyszczona.',

	'RS_GIVE_POINT'					=> 'Przyznaj punkt reputacji',
	'RS_GIVE_POINT_EXPLAIN'			=> 'Tutaj możesz przyznać punkt reputacji innemu użytkownikowi.',

	'RS_RANKS'						=> 'Zarządzanie rangami',
	'RS_RANKS_EXPLAIN'				=> 'Tutaj możesz dodać, edytować, usuwać rangi bazowane na punktach reputacji.',
	'RS_ADD_RANK'					=> 'Dodaj rangę',
	'RS_MUST_SELECT_RANK'			=> 'Musisz wybrać rangę',
	'RS_NO_RANK_TITLE'				=> 'Musisz określić tytuł rangi',
	'RS_RANK_ADDED'					=> 'Ranga została dodana pomyślnie.',
	'RS_RANK_MIN'					=> 'Minimum punktów',
	'RS_RANK_TITLE'					=> 'Tytuł rangi',
	'RS_RANK_IMAGE'					=> 'Obraz rangi',
	'RS_RANK_COLOR'					=> 'Kolor rangi',
	'RS_RANK_UPDATED'				=> 'Ranga została zaktualizowana pomyślnie.',
	'RS_IMAGE_IN_USE'				=> '(w użyciu)',
	'RS_RANKS_ON'					=> '<span style="color:green;">Rangi reputacji są włączone.</span>',
	'RS_RANKS_OFF'					=> '<span style="color:red;">Rangi reputacji są wyłączone.</span>',

	'RS_BANS'						=> 'Zarządzanie banami za reputację',
	'RS_BANS_EXPLAIN'				=> 'Tutaj możesz dodać, edytować, usuwać bany bazowane na punktach reputacji.',
	'RS_BAN_POINT'					=> 'Punkty do bana',
	'RS_AUTO_BAN_REASON'			=> 'Auto-ban za niską reputację',
	'RS_ADD_BAN'					=> 'Dodaj ban',
	'RS_BAN_ADDED'					=> 'Ban został dodany pomyślnie.',
	'RS_BAN_UPDATED'				=> 'Ban został zaktualizowany pomyślnie.',
	'RS_OTHER'						=> 'Inny',
	'RS_MINUTES'					=> 'minuty',
	'RS_HOURS'						=> 'godziny',
	'RS_DAYS'						=> 'dni',

	'RS_FORUM_REPUTATION'			=> 'Włącz reputację',
	'RS_FORUM_REPUTATION_EXPLAIN'	=> 'Zezwól użytkownikom na ocenę postów. Możesz wybrać, czy ocenianie postu ma wpływać na reputację użytkownika.',
	'RS_POST_WITH_USER'				=> 'Tak, z wpływem na reputację użytkownika',
	'RS_POST_WITHOUT_USER'			=> 'Tak, bez wpływu na reputację użytkownika',

	'LOG_REPUTATION_SETTING'		=> '<strong>Zmieniono ustawienia Systemu Reputacji</strong>',
	'LOG_REPUTATION_SYNC'			=> '<strong>System Reputacji został zsynchronizowany</strong>',
	'LOG_REPUTATION_TRUNCATE'		=> '<strong>Wyczyszczono reputację</strong>',
	'LOG_RS_BAN_ADDED'				=> '<strong>Dodano nowy ban za reputację</strong>',
	'LOG_RS_BAN_REMOVED'			=> '<strong>Usunięto ban za reputację</strong>',
	'LOG_RS_BAN_UPDATED'			=> '<strong>Zaktualizowano ban za reputację</strong>',
	'LOG_RS_RANK_ADDED'				=> '<strong>Dodano nowę rangę reputacji</strong><br />» %s',
	'LOG_RS_RANK_REMOVED'			=> '<strong>Usunięto rangę reputacji</strong><br />» %s',
	'LOG_RS_RANK_UPDATED'			=> '<strong>Zaktualizowano rangę reputacji</strong><br />» %s',
	'LOG_USER_REP_DELETE'			=> '<strong>Usunięto punkt reputacji</strong><br />Użytkownik: %s',
	'LOG_CLEAR_POST_REP'			=> '<strong>Wyczyszczono punkty reputacji</strong><br />Post: %s',
	'LOG_CLEAR_USER_REP'			=> '<strong>Wyczyszczono punkty reputacji</strong><br />Użytkownik: %s',

	'IMG_ICON_RATE_GOOD'			=> 'Oceń pozytywnie',
	'IMG_ICON_RATE_BAD'				=> 'Oceń negatywnie',

	//Installation
	'FILES_NOT_EXIST'				=> 'Ikony oceniania:<br />%s<br /> nie istnieją.<br /><br /><strong>Zanim zaczniejsz instlację Ssytemu Reputacji, skopiuj ikony oceniania z <em>contrib/images</em> do folderów imageset styli, których używasz. Następnie odśwież tą stronę.</strong>',
	'CONVERT_THANKS'				=> 'Konwertuj Thanks for posts do Systemu Reputacji',
	'CONVERT_KARMA'					=> 'Konwertuj Karma MOD do Systemu Reputacji',
	'CONVERT_HELPMOD'				=> 'Konwertuj HelpMOD do Systemu Reputacji',
	'CONVERT_LIKE'					=> 'Konwertuj phpBB Ajax Like do Systemu Reputacji',
	'CONVERT_THANK'					=> 'Konwertuj Thank You Mod do Systemu Reputacji',
	'CONVERT_DATA'					=> 'Skonwertowane modyfikacje: %1$s.<br />Teraz możesz odinstalować %2$s. Przejdź do Panelu administracji i wykonaj synchronizację Systemu Reputacji.',
	'UPDATE_RS_TABLE'				=> 'Tabela reputacji została zaktualizowana pomyślnie.',

	//MOD Version Check
	'ANNOUNCEMENT_TOPIC'		=> 'Ogłoszenie o wydaniu',
	'CURRENT_VERSION'			=> 'Zainstalowana wersja',
	'DOWNLOAD_LATEST'			=> 'Pobierz najnowszą wersję',
	'LATEST_VERSION'			=> 'Najnowsza wersja',
	'NO_INFO'					=> 'Nie można połączyć się z serwerem',
	'NOT_UP_TO_DATE'			=> '%s nie jest zaktualizowany',
	'RELEASE_ANNOUNCEMENT'		=> 'Ogłoszenie',
	'UP_TO_DATE'				=> 'Korzystasz z najnowszej wersji %s. Aktualizacja nie jest wymagana.',
	'VERSION_CHECK'				=> 'Sprawdzanie wersji modyfikacji',
));

?>
