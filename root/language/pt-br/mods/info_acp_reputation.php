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
	'REPUTATION_SYSTEM'				=> 'Sistema de Reputação',

	'ACP_REPUTATION_SYSTEM'				=> 'Sistema de Reputação',
	'ACP_REPUTATION_SYSTEM_EXPLAIN'		=> 'A partir daqui você pode visualizar todas as configurações e funções do Sistema de Reputação necessárias para gerenciá-lo.',
	'ACP_REPUTATION_OVERVIEW'			=> 'Visão Geral',
	'ACP_REPUTATION_SETTINGS'			=> 'Configurações',
	'ACP_REPUTATION_SETTINGS_EXPLAIN'	=> 'Nesta página você pode configurar todas as funções do sistema de reputação. Tudo está dividido em grupos.',
	'ACP_REPUTATION_GIVE'				=> 'Conceder ponto',
	'ACP_REPUTATION_RANKS'				=> 'Ranks',
	'MCP_REPUTATION'					=> 'Reputação',
	'MCP_REPUTATION_FRONT'				=> 'Página Inicial',
	'MCP_REPUTATION_LIST'				=> 'Listar reputações',
	'MCP_REPUTATION_GIVE'				=> 'Conceder ponto',
	'UCP_REPUTATION'					=> 'Reputação',
	'UCP_REPUTATION_FRONT'				=> 'Página inicial',
	'UCP_REPUTATION_LIST'				=> 'Listar',
	'UCP_REPUTATION_GIVEN'				=> 'Conceder pontos',
	'UCP_REPUTATION_SETTING'			=> 'Configurações',

	'ACP_RS_MAIN'			=> 'Geral',
	'ACP_RS_DISPLAY'		=> 'Display',
	'ACP_RS_POSTS_RATING'	=> 'Posts rating',
	'ACP_RS_USERS_RATING'	=> 'Users rating',
	'ACP_RS_COMMENT'		=> 'Comments',
	'ACP_RS_POWER'			=> 'Reputation power',
	'ACP_RS_RANKS'			=> 'Ranks',
	'ACP_RS_TOPLIST'		=> 'Toplist',

	'RS_ENABLE'		=> 'Habilitar sistema de reputação',

	'RS_NEGATIVE_POINT'				=> 'Permitir pontos negativos',
	'RS_NEGATIVE_POINT_EXPLAIN'		=> 'Quando usuários desabilitados não podem conceder pontos negativos.',
	'RS_MIN_REP_NEGATIVE'			=> 'Reputação mínima para conceder pontos negativos',
	'RS_MIN_REP_NEGATIVE_EXPLAIN'	=> 'Quanto de reputação é necessário para conceder pontos negativos. Configurando o valor em 0 desabilita este comportamento.',
	'RS_WARNING'					=> 'Habilitar avisos',
	'RS_WARNING_EXPLAIN'			=> 'Usuários com permissões adequadas podem conceder pontos negativos quando estiverem avisando usuários.',
	'RS_NOTIFICATION'				=> 'Habilitar notificações',
	'RS_NOTIFICATION_EXPLAIN'		=> 'Esta opção habilita notificações sobre novos pontos de reputação no cabeçalho.',
	'RS_PM_NOTIFY'					=> 'Habilita notificação via mensagem privada',
	'RS_PM_NOTIFY_EXPLAIN'			=> 'Esta opção permite usuários a enviar uma notificação via mensagem privada quando há novos pontos de reputação.',
	'RS_MIN_POINT'					=> 'Mínimo de pontos',
	'RS_MIN_POINT_EXPLAIN'			=> 'Limita o mínimo de pontos de reputação que um usuário vai receber. Configurar este valor em 0 desabilita este comportamento.',
	'RS_MAX_POINT'					=> 'Máximo de pontos',
	'RS_MAX_POINT_EXPLAIN'			=> 'Limita o máximo de pontos que um usuário pode receber. Configurar este valor em 0 desabilita este comportamento.',
	'RS_PREVENT_OVERRATING'			=> 'Prevenindo o 'overrating' ( super avalição do usuário )',
	'RS_PREVENT_OVERRATING_EXPLAIN'	=> 'Bloquear usuários a avaliar o mesmo usuário.<br /><em>Exemplo:</em> se o usuário A tem mais de 10 pontos de reputação e 85% vem do usuário B, o usuário B não pode qualificar este usuário enquanto sua relação de votos for maior que 85%.<br />Para desabilitar esta função é só configurar um ou todos os valores como 0.',
	'RS_PREVENT_NUM'				=> 'O total de entradas de reputação do usuário A é igual ou maior que',
	'RS_PREVENT_PERC'				=> '<br />e a relalão dos votos do usuário B é igual ou maior a',

	'RS_PER_PAGE'							=> 'Reputações por página',
	'RS_PER_PAGE_EXPLAIN'					=> 'Quantas linhas nós devemos mostrar na sua tabela de reputações ?',
	'RS_DISPLAY_AVATAR'						=> 'Mostrar avatares',
	'RS_SORT_MEMBERLIST_BY_REPO'			=> 'Ordenar a lista de membros por reputação',
	'RS_SORT_MEMBERLIST_BY_REPO_EXPLAIN'	=> 'Quando a lista de membros está sendo ordenada por reputação é mais sensato checar ela regularmente para ver como tudo está andando. Você pode voltar esta configuração para 'off' ( desligado ) para voltar ao padrão que é ordenar por nome de usuário.',
	'RS_POINT_TYPE'							=> 'Método para mostrar os pontos',
	'RS_POINT_TYPE_EXPLAIN'					=> 'A visualização dos pontos de reputação pode ser feita a partir do valor exato de pontos de reputação que o usuário recebeu ou uma imagem mostrando mais ou menos para ponto positivos ou negativos. O método de imagem é mais usual se você configurou o envio dos pontos de reputação de para um ( ou seja, um ponto por vez ).',
	'RS_POINT_VALUE'						=> 'Valor',
	'RS_POINT_IMG'							=> 'Imagem',

	'RS_POST_RATING'				=> 'Habilitar avaliação de posts',
	'RS_ALLOW_REPUTATION_BUTTON'	=> 'Submeter e habilitar o Sistema de Reputação em todos os forums',
	'RS_HIGHLIGHT_POST'				=> 'Destacando um post',
	'RS_HIGHLIGHT_POST_EXPLAIN'		=> 'Post with rating higer than the number set here will be highlighted. Setting the value to 0 disables this behaviour.<br /><em>Note:</em> You can modify default highlighting by editing <strong>highlight</strong> class in reputation.css.',
	'RS_HIDE_POST'					=> 'Esconder posts com poucas avaliações',
	'RS_HIDE_POST_EXPLAIN'			=> 'Posts with a rating less than the number set here will be hidden by default (users have the option to unhide them if they choose). After a post has earned a rating greater than this value, it will no longer be hidden by default. Setting the value to 0 disables this behaviour.',
	'RS_ANTISPAM'					=> 'Anti-Spam',
	'RS_ANTISPAM_EXPLAIN'			=> 'Block users from rating any more posts after they have rated the defined number of posts within the defined number of hours. To disable this feature set one or both values to 0.',
	'RS_POSTS'						=> 'Number of rated posts',
	'RS_HOURS'						=> 'in the last hours',
	'RS_ANTISPAM_METHOD'			=> 'Anti-Spam check method',
	'RS_ANTISPAM_METHOD_EXPLAIN'	=> 'Method for checking Anti-Spam. “Same user” method checks reputation given to the same user. “All users” method checks reputation regardless of who received points.',
	'RS_SAME_USER'					=> 'Same user',
	'RS_ALL_USERS'					=> 'All users',

	'RS_USER_RATING'				=> 'Allow rating of users from their profile page',
	'RS_USER_RATING_GAP'			=> 'Voting gap',
	'RS_USER_RATING_GAP_EXPLAIN'	=> 'Time period a user must wait before they can give another rating to a user they have already rated. Setting the value to 0 disables this behaviour and users can rate other users once each.',

	'RS_ENABLE_COMMENT'				=> 'Enable comments',
	'RS_ENABLE_COMMENT_EXPLAIN'		=> 'When enabled, users will be able to add a personal comment with their rating.',
	'RS_FORCE_COMMENT'				=> 'Force user to enter comment',
	'RS_FORCE_COMMENT_EXPLAIN'		=> 'Users will be required to add a comment with their rating.',
	'RS_COMMENT_NO'					=> 'No',
	'RS_COMMENT_BOTH'				=> 'Both user and post ratings',
	'RS_COMMENT_POST'				=> 'Only post ratings',
	'RS_COMMENT_USER'				=> 'Only user ratings',
	'RS_COMMEN_LENGTH'				=> 'Comment length',
	'RS_COMMEN_LENGTH_EXPLAIN'		=> 'The number of characters allowed within a comment. Set to 0 for unlimited characters.',

	'RS_ENABLE_POWER'				=> 'Enable reputation power',
	'RS_ENABLE_POWER_EXPLAIN'		=> 'Reputation power is something that users earn and spend on voting. New users have low power, active and veteran users gain more power. The more power you have the more you can vote during a specified period of time and the more influence you can have on the rating of another user or post.<br/>Users can choose during voting how much power they will spend on a vote, giving more points to interesting posts.',
	'RS_POWER_RENEWAL'				=> 'Power renewal time',
	'RS_POWER_RENEWAL_EXPLAIN'		=> 'This controls how users can spend earned power.<br/>If you set this option, users must wait for the given time interval before they can vote again. The more reputation power a user has, the more points they can spend in the set time.<br/>Recommended 5 hours.<br />Setting the value to 0 disables this behaviour and users can vote without waiting.',
	'RS_MIN_POWER'					=> 'Starting/Minimum reputation power',
	'RS_MIN_POWER_EXPLAIN'			=> 'This is how much reputation power newly registered users, banned users and users with low reputation or other criteria have. Users can’t go lower than this minimum voting power.<br/>Allowed 0-10. Recommended 1.',
	'RS_MAX_POWER'					=> 'Maximum power spending per vote',
	'RS_MAX_POWER_EXPLAIN'			=> 'Maximum amount of power that a user can spend per vote. Even if a user has millions of points, they’ll still be limited by this maximum number when voting.<br/>Users will select this from dropdown menu: 1 to X<br/>Allowed 1-20. Recommended: 3.',
	'RS_MAX_POWER_WARNING'			=> 'Maximum reputation power for warnings',
	'RS_MAX_POWER_WARNING_EXPLAIN'	=> 'Maximum reputation power allowed for warnings.',
	'RS_MAX_POWER_BAN'				=> 'Maximum reputation power for bans',
	'RS_MAX_POWER_BAN_EXPLAIN'		=> 'Maximum reputation power a user gets if they are banned for 1 month or permanently. If a user is banned for a shorter period of time, they will receive a relative number of points.',
	'RS_POWER_EXPLAIN'				=> 'Reputation power explanation',
	'RS_POWER_EXPLAIN_EXPLAIN'		=> 'Explain how reputation power is calculated to users.',
	'RS_TOTAL_POSTS'				=> 'Gain power with number of posts',
	'RS_TOTAL_POSTS_EXPLAIN'		=> 'User will gain 1 reputation power every X number of posts set here.',
	'RS_MEMBERSHIP_DAYS'			=> 'Gain power with length of the user’s membership',
	'RS_MEMBERSHIP_DAYS_EXPLAIN'	=> 'User will gain 1 reputation power every X number of days set here',
	'RS_POWER_REP_POINT'			=> 'Gain power with the user’s reputation',
	'RS_POWER_REP_POINT_EXPLAIN'	=> 'User will gain 1 reputation power every X number of reputation points they earn set here.',
	'RS_LOSE_POWER_BAN'				=> 'Lose power with bans',
	'RS_LOSE_POWER_BAN_EXPLAIN'		=> 'Each ban within the last year decreases reputation power by this amount of points',
	'RS_LOSE_POWER_WARN'			=> 'Lose power with warnings',
	'RS_LOSE_POWER_WARN_EXPLAIN'	=> 'Each warning decreases reputation power by this amount of points. Warnings expire in accordance with the settings in General -> Board Configuration -> Board settings',
	'RS_GROUP_POWER'				=> 'Group reputation power',

	'RS_RANKS_ENABLE'				=> 'Enable ranks',
	'RS_RANKS_PATH'					=> 'Reputation rank image storage path',
	'RS_RANKS_PATH_EXPLAIN'			=> 'Path in your phpBB root directory, e.g. <samp>images/reputation</samp>.',

	'RS_ENABLE_TOPLIST'				=> 'Enable Toplist',
	'RS_ENABLE_TOPLIST_EXPLAIN' 	=> 'Display a list of users with the most reputation points on the index page.',
	'RS_TOPLIST_DIRECTION'			=> 'Direction of list',
	'RS_TOPLIST_DIRECTION_EXPLAIN'	=> 'Display the users in the list in a horizontal or vertical direction.',
	'RS_TL_HORIZONTAL'				=> 'Horizontal',
	'RS_TL_VERTICAL'				=> 'Vertical',
	'RS_TOPLIST_NUM'				=> 'Number of Users to Display',
	'RS_TOPLIST_NUM_EXPLAIN'		=> 'Number of users displayed on the toplist.',

	'RS_SYNC'						=> 'Resynchronise reputations',
	'RS_SYNC_EXPLAIN'				=> 'You can resynchronise Reputation System after a mass removal of posts/topics/users, changing reputation settings, changing post authors, conversions from others systems. This may take a while. You will be notified when the process is completed.<br /><strong>Warning!</strong> During synchronization will be deleted reputation points that do not match the reputation settings. It is recommended to make backup of the reputation table (DB) before synchronisation.',
	'RS_SYNC_STEP_DEL'				=> 'Step 1/7 - removing reputation points of non-existent users',
	'RS_SYNC_STEP_POSTS_DEL'		=> 'Step 2/7 - removing reputation points of deleted posts',
	'RS_SYNC_STEP_REPS_DEL'			=> 'Step 3/7 - removing reputations, which do not match reputation settings',
	'RS_SYNC_STEP_POST_AUTHOR'		=> 'Step 4/7 - checking author of a post and synchronising reputation entry if it was changed',
	'RS_SYNC_STEP_FORUM'			=> 'Step 5/7 - checking forum settings and synchronising post reputation influence on user reputation',
	'RS_SYNC_STEP_USER'				=> 'Step 6/7 - synchronisation of users reputations',
	'RS_SYNC_STEP_POSTS'			=> 'Step 7/7 - synchronisation of posts reputations',
	'RS_SYNC_DONE'					=> 'Reputation System synchronisation has finished successfully',
	'RS_RESYNC_REPUTATION_CONFIRM'	=> 'Are you sure you wish to resynchronise reputations?',

	'RS_TRUNCATE'				=> 'Clear Reputation System',
	'RS_TRUNCATE_EXPLAIN'		=> 'This procedure completely removes all data.<br /><strong>Action is not reversible!</strong>',
	'RS_TRUNCATE_CONFIRM'		=> 'Are you sure you wish to clear Reputation System?',
	'RS_TRUNCATE_DONE'			=> 'Reputações foram limpas.',

	'RS_GIVE_POINT'				=> 'Enviar pontos de reputação',
	'RS_GIVE_POINT_EXPLAIN'		=> 'Aqui você pode enviar pontos adicionais de reputação aos usuários.',

	'RS_RANKS'					=> 'Gerenciar os ranks',
	'RS_RANKS_EXPLAIN'			=> 'Aqui você pode adicionar, editar, ver e deletar ranks baseado nos pontos de reputação. ',
	'RS_ADD_RANK'				=> 'Adicionar Rank',
	'RS_MUST_SELECT_RANK'		=> 'Você tem que selecionar um rank',
	'RS_NO_RANK_TITLE'			=> 'Você tem que especificfar um nome para o rank',
	'RS_RANK_ADDED'				=> 'O rank foi adicionado com sucesso.',
	'RS_RANK_MIN'				=> 'Mínimo de pontos',
	'RS_RANK_TITLE'				=> 'Título do rank',
	'RS_RANK_IMAGE'				=> 'Imagem do rank',
	'RS_RANK_COLOR'				=> 'Cor do rank',
	'RS_RANK_UPDATED'			=> 'O rank foi atualizado com sucesso.',
	'RS_IMAGE_IN_USE'			=> '(Em uso)',
	'RS_RANKS_ON'				=> '<span style="color:green;">Ranks de reputação estão ligados.</span>',
	'RS_RANKS_OFF'				=> '<span style="color:red;">Ranks de reputação estão desligados.</span>',
	'RS_NO_RANKS'				=> 'Não há ranks de reputação',

	'RS_FORUM_REPUTATION'			=> 'Habilitar reputação',
	'RS_FORUM_REPUTATION_EXPLAIN'	=> 'Permitir usuário a avaliar os posts. Você pode escolher se a avaliação dos posts vai influenciar na reputação do usuário.',
	'RS_POST_WITH_USER'				=> 'Sim, com influência no sistema de reputação',
	'RS_POST_WITHOUT_USER'			=> 'Sim, sem influência na reputação do usuário',

	'LOG_REPUTATION_SETTING'		=> '<strong>Configurações do sistema de reputação foram modificadas</strong>',
	'LOG_REPUTATION_SYNC'			=> '<strong>Sistema de reputação foi resincronizado</strong>',
	'LOG_REPUTATION_TRUNCATE'		=> '<strong>Reputações limpas</strong>',
	'LOG_RS_RANK_ADDED'				=> '<strong>Novo rank de reputação foi adicionado</strong><br />» %s',
	'LOG_RS_RANK_REMOVED'			=> '<strong>Rank de reputação foi removido</strong><br />» %s',
	'LOG_RS_RANK_UPDATED'			=> '<strong>Rank de reputação foi atualizado</strong><br />» %s',
	'LOG_USER_REP_DELETE'			=> '<strong>O ponto de reputação do usuário foi deletado</strong><br />Usuário: %s',
	'LOG_CLEAR_POST_REP'			=> '<strong>Reputação do post foi limpa</strong><br />Post: %s',
	'LOG_CLEAR_USER_REP'			=> '<strong>Reputação do usuário foi limpa</strong><br />Usuário: %s',

	'IMG_ICON_RATE_GOOD'		=> 'Avaliar como bom',
	'IMG_ICON_RATE_BAD'			=> 'Avaliar como ruim',

	//Installation
	'FILES_NOT_EXIST'		=> 'Os ícones de avaliação:<br />%s<br /> não foi encontrado.<br /><br /><strong>Antes de continuar, você precisa copiar os ícones de avalição no diretório <em>contrib/images</em> do diretório de imageset do estilo que você está usando. Aí, faça o refresh da página.<strong>',
	'CONVERTER'				=> 'Converter',
	'CONVERT_THANKS'		=> 'Converter o Thanks for posts para o sistema de reputação',
	'CONVERT_KARMA'			=> 'Converter o karma MOD para o sistema de reputação',
	'CONVERT_HELPMOD'		=> 'Converter o HelpMOD para o sistema de reputação',
	'CONVERT_LIKE'			=> 'Converter o phpBB Ajax Like para o sistema de reputação',
	'CONVERT_THANK'			=> 'Converter o módulo 'Thank You' para o sistema de Reputação',
	'CONVERT_DATA'			=> 'MOD convertido: %1$s.<br />Agora, você pode desinstalar %2$s. Vá ao ACP e efetue a resincronização do sistema de reputação.',
	'UPDATE_RS_TABLE'		=> 'A tabela de reputação foi atualizada.', 

	//MOD Version Check
	'ANNOUNCEMENT_TOPIC'		=> 'Anúncio de lançamento',
	'CURRENT_VERSION'			=> 'Versão atual',
	'DOWNLOAD_LATEST'			=> 'Fazer o download da última versão',
	'LATEST_VERSION'			=> 'Última versão',
	'NO_INFO'					=> 'O servidor de versão não pode ser contactado',
	'NOT_UP_TO_DATE'			=> '%s não está atualizado',
	'RELEASE_ANNOUNCEMENT'		=> 'Annoucement Topic',
	'UP_TO_DATE'				=> '%s está atualizado',
	'VERSION_CHECK'				=> 'Checador de versão do MOD',
));

?>
