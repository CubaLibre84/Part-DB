<?PHP
/*
    part-db version 0.1
    Copyright (C) 2005 Christoph Lechner
    http://www.cl-projects.de/

    This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License
    as published by the Free Software Foundation; either version 2
    of the License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA

    $Id$

*/
    include ("lib.php");
    partdb_init();

    /*
     * 'action' is a hidden field in the form.
     * The 'instock' value has to be changed before the output begins.
     */

    // set action to default, if not exists
    $action = ( isset( $_REQUEST["action"]) ? $_REQUEST["action"] : 'default');

    if ( $action == "dec")
    {
        parts_stock_decrease( $_REQUEST["pid"], $_REQUEST["n_less"]);
    }
    
    if ( $action == "inc")
    {
        parts_stock_increase( $_REQUEST["pid"], $_REQUEST["n_more"]);
    }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
          "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>Detailinfo</title>
    <?php print_http_charset(); ?>
    <link rel="StyleSheet" href="css/partdb.css" type="text/css">
    <script type="text/javascript" src="popup.php"></script>
</head>
<body class="body">

<div class="outer">
    <h2>Detailinfo zu &quot;<?PHP print lookup_part_name ($_REQUEST["pid"]); ?>&quot;</h2>
    <div class="inner">
        
        <table>
        <tr valign="top">
        <td>
        <table><?php
        $result = parts_select( $_REQUEST["pid"]);
        while ( $data = mysql_fetch_assoc( $result))
        {
            print "<tr><td><b>Name:</b></td><td>". smart_unescape( $data['name']) ."</td></tr>". PHP_EOL;
            print "<tr><td><b>Vorhanden:</b></td><td>". smart_unescape( $data['instock']) ."</td></tr>". PHP_EOL;
            print "<tr><td><b>Min. Bestand:</b></td><td>". smart_unescape( $data['mininstock']) ."</td></tr>". PHP_EOL;
            print "<tr><td><b>Footprint:</b></td><td>". smart_unescape( $data['footprint']) ."</td></tr>". PHP_EOL;
            print "<tr><td><b>Lagerort:</b></td><td>". smart_unescape( $data['location']). (( $data['location_is_full'] == 1 ) ? ' [voll]' : '') ."</td></tr>". PHP_EOL;
            print "<tr><td><b>Lieferant:</b></td><td>". smart_unescape( $data['supplier']) ."</td></tr>". PHP_EOL;
            print "<tr><td><b>Bestell-Nr.:</b></td><td>". smart_unescape( $data['supplierpartnr']) ."</td></tr>". PHP_EOL;
            $preis = str_replace('.', ',', $data['preis']);
            print "<tr><td><b>Preis:</b></td><td>". smart_unescape( $preis). PHP_EOL;
            include("config.php");
            print " ".$currency." &nbsp;</td></tr>". PHP_EOL;
            print "<tr><td valign=\"top\"><b>Kommentar:</b></td><td>". nl2br( smart_unescape( $data['comment'])) ."&nbsp;</td></tr>". PHP_EOL;
        }
        ?>
        </table>
        <br>Angaben <a href="editpartinfo.php?pid=<?PHP print $_REQUEST["pid"]; ?>">ver&auml;ndern</a>
        </td>
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td>
        <table>
        <form action="" method="post">
        <input type="hidden" name="pid" value="<?PHP print $_REQUEST["pid"]; ?>">
        <input type="hidden" name="action" value="dec">
        <tr><td colspan="2">Teile entnehmen</td></tr>
        <tr valign="top">
        <td>Anzahl:</td><td><input type="text" size="3" name="n_less" value="1"></td>
        </tr><tr><td colspan="2" align="center"><input type="submit" value="Entnehmen!"></td></tr>
        </form>
        <tr><td colspan="2">&nbsp;</td></tr>
        <form action="" method="post">
        <input type="hidden" name="pid" value="<?PHP print $_REQUEST["pid"]; ?>">
        <input type="hidden" name="action" value="inc">
        <tr><td colspan="2">Teile hinzuf&uuml;gen</td></tr>
        <tr valign="top">
        <td>Anzahl:</td><td><input type="text" size="3" name="n_more" value="1"></td>
        </tr><tr><td colspan="2" align="center"><input type="submit" value="Hinzuf&uuml;gen!"></td></tr>
        </form>
        </table>
        </td>
        </tr>
        </table>
        <?php
        if ( has_image( $_REQUEST["pid"]))
        {
            print "<br><b>Bilder:</b><table><tr>". PHP_EOL;
            
            $pict_query = "SELECT pictures.id FROM pictures WHERE (pictures.part_id=". smart_escape($_REQUEST["pid"]) .") AND (pictures.pict_type='P');";
            debug_print ($pict_query);
            $result = mysql_query ($pict_query);

            while ($data = mysql_fetch_assoc( $result))
            {
                print "<td><a href=\"javascript:popUp('getimage.php?pict_id=". $data['id'] ."')\"><img src=\"getimage.php?pict_id=". $data['id'] ."&maxx=200&maxy=150\" alt=\"Zum Vergr&ouml;&szlig;ern klicken!\"></a></td>". PHP_EOL;
            }
            print "</tr></table>". PHP_EOL;
        }
        ?>
    </div>
</div>


</body>
</html>
