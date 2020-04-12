#!/usr/bin/perl
use DBI;
use Encode qw(encode decode);
my $enc = 'utf-8';
my ($dsn) = "DBI:mysql:area:localhost";
my ($username) = "root";
my ($password) = "enunlugar";
my ($dbh, $sth);
$dbh = DBI->connect($dsn, $username, $password, {RaiseError => 1});

# on regarde quelle option est prise
if ($ARGV[0] eq "-h" or $ARGV[0] eq "--help") {
	print "-nus (or --nameUS) for updating US players Data base\n";
	print "-neu (or --nameEU) for updating EU players Data base\n";
	print "-nT (or --nameT) for updating players who have played\n";
	print "-m (or --mygames) for updating personal Data base\n";
	print "-r (or --roar) for updating ROAR data base\n";
	print "-t (or --tournament) for updating Tournament data base\n";
	print "-t2 (or --tournament2) for updating t_tourneys data base\n";
	print "-g (or --games) for updating Tourney games data base\n";
	print "-g2 (or --games2) TOURN (not tour_yy) for updating t2_games table in the data base\n";
	print "-s (or --scen) for updating scenarios table in data base\n";
	exit;
} elsif ($ARGV[0] eq "-r" or $ARGV[0] eq "--roar") {
	majRoar();
	exit;
} elsif ($ARGV[0] eq "-s" or $ARGV[0] eq "--scen") {
	majScen();
	exit;
} elsif ($ARGV[0] eq "-nus" or $ARGV[0] eq "--nameUS") {
	$fic = "DBase/usN.db";
	majNames();
	exit;
} elsif ($ARGV[0] eq "-neu" or $ARGV[0] eq "--nameEU") {
	$fic = "DBase/euN.db";
	majNames();
	exit;
} elsif ($ARGV[0] eq "-nT" or $ARGV[0] eq "--nameT") {
	$fic = "DBase/initT.db";
	majNames();
	exit;
} elsif ($ARGV[0] eq "-m" or $ARGV[0] eq "--mygames") {
	majMyGames();
	exit;
} elsif ($ARGV[0] eq "-t" or $ARGV[0] eq "--tournament") {
	majTournaments();
	exit;
} elsif ($ARGV[0] eq "-t2" or $ARGV[0] eq "--tournament2") {
	majTournaments2();
	exit;
} elsif ($ARGV[0] eq "-g" or $ARGV[0] eq "--games") {
	majGames($ARGV[1]);
	exit;
} elsif ($ARGV[0] eq "-g2" or $ARGV[0] eq "--games2") {
	majGames2($ARGV[1]);
	exit;
} else {
	print "type -h or --help to obtain help\n";
	exit;
}

sub majMyGames {
	open (H,"scen.db") or die "$! fichier introuvable";
	while (<H>) {
		chomp;
		($scen_short,$f_short,$s_short,
		$f_res,$f_side,$f_pos,
		$mode,$type,$year,$month,$day) = split(/,/);
		$sth=$dbh->prepare(
			qq(SELECT scen_short,f_short,s_short,year,month,day 
			FROM my_games WHERE 
			scen_short=\"$scen_short\" AND
			f_short=\"$f_short\" AND
			s_short=\"$s_short\" AND
			year=\"$year\" AND
			month=\"$month\" AND
			day=\"$day\" ));
		$sth->execute();
		if ($sth->fetchrow_array()) {
			print "Ce résultat $scen_short) existe deja...\n";
		} else {
			$rows = $dbh->do(
				"INSERT INTO my_games ". 
			"(scen_short,f_short,s_short, ".
			"f_res,f_side,f_pos,mode, type,year,month,day )".
			 	"VALUES (".
			"\"$scen_short\",".
			"\"$f_short\",".
			"\"$s_short\",".
			"\"$f_res\",".
			"\"$f_side\",".
			"\"$f_pos\",".
			"\"$mode\",".
			"\"$type\",".
			"\"$year\",".
			"\"$month\",".
			"\"$day\" )" 	);
			die "pb sur la ligne $scen_short,$f_short...\n" 
				if (!$rows);
		}
	}
	close (H);
}

sub majRoar {
	open(H,"DBase/roar.db") or die "$! fichier introuvable";

	while (<H>) {
		chomp;
		($title,$short,$defnat,$defres,$attnat,$attres ) = split(/;/);
		$sth=$dbh->prepare(
			qq(SELECT short FROM roar WHERE 
			short=\"$short\"));
		$sth->execute();
		if ($sth->fetchrow_array()) {
			print "Le scenario $title ($short) existe deja...\n";
		} else {
			$rows = $dbh->do('INSERT INTO roar 
			(title, short,defnat,defres,attnat,attres)'
			.' VALUES ('.	$title.",\"$short\",
					\"$defnat\",\"$defres\",
					\"$attnat\",\"$attres\" )");
			die "probleme sur la ligne $title,$short...\n" 	
				if (!$rows);
		}
	}
	close (H);
}

sub majScen {
	open(H,"DBase/scenarios.db") or die "$! fichier introuvable";
	<H>;
	while (<H>) {
		chomp;
		( $id,$name,$code,$date,$location,$designer,$publication_id,
		  $allied_side,$axis_side,$defenders,$previous_code,$errata,
		  $allied_none,$axis_none,$allied_allied,$axis_allied,
		  $allied_axis,$axis_axis,$total_rating,$number_of_ratings,
		  $created_at,$updated_at,$boards,$terrain,$turns,$features,
		  $campaign_game,$afvs,$possess,$official
			) = split(/;/);
		$sth=$dbh->prepare(
			qq(SELECT code FROM scenarios WHERE code=$code) );
		$sth->execute();
		if ($sth->fetchrow_array()) {
			print "Le scenario $code existe deja...\n";
		} else {
			$rows = $dbh->do('INSERT INTO scenarios 
			(id,name,code,date,location,designer,publication_id,
			allied_side,axis_side,defenders,previous_code,errata,
			allied_none,axis_none,allied_allied,axis_allied,
			allied_axis,axis_axis,total_rating,number_of_ratings,
			created_at,updated_at,boards,terrain,turns,features,
			campaign_game,afvs,possess,official)'
			.' VALUES ('.	
			qq($id,$name,$code,$date,$location,$designer,$publication_id,
			$allied_side,$axis_side,$defenders,$previous_code,$errata,
			$allied_none,$axis_none,$allied_allied,$axis_allied,
			$allied_axis,$axis_axis,$total_rating,$number_of_ratings,
			$created_at,$updated_at,$boards,$terrain,$turns,$features,
			$campaign_game,$afvs,$possess,$official).')' );

			die "probleme sur la ligne $title,$short...\n" 	
				if (!$rows);
			print "on a ajouté correctement $code\n";
		}
	}
	close (H);
}
sub majNames {
	open(H,$fic) or die "$! fichier introuvable";
	#open(H,"ASLOpen/us.db") or die "$! fichier introuvable";

	LINE: while (<H>) {
		chomp;
		next LINE if /^#.*/;
		$ligne=$_;
		($name, $first_n, $diminutif, $country, $trig,
		$status, $firstT,$nb_type,
		$area, $lastT, $nbGames,$comment )=split(/,/);
		#$name = decode ($enc, $name);
		#$first_n = decode ($enc, $first_n);
		$area='"NIL"' if ($area eq '""');
		$diminutif='"NIL"' if ($diminutif eq '""');
		$lastT='"NIL"' if ($lastT eq '""');
		$nbGames='"0"' if ($nbGames eq '""');
		$sth=$dbh->prepare(
			qq(SELECT name FROM players WHERE trig=$trig));
		$sth->execute();
		if ($sth->fetchrow_array()) {
			print "Le joueur ($trig / $name $first_n) existe deja...\n";
		} else {
		# on maj la table des joueurs
			print "on cree les references du joueur $name\n";
			$rows = $dbh->do('INSERT INTO players 
				(name, first_n, diminutif, country, trig)'
		." VALUES ($name, $first_n, $diminutif, $country, $trig)");
			die "probleme sur le joueur $trig...\n" 	
				if (!$rows);
		# on maj la table des initialisations
			if ($area eq '"NIL"') {
				print "$trig n est pas classe...\n";
			} else {
				$rows = $dbh->do('INSERT INTO init_area ' 
			.'(trig,area,init_type,firstT,lastT,nbGamesInit)'
			." VALUES ($trig,$area,"
			."$status,$firstT,$lastT,$nbGames)");
			#." VALUES (\"$trig\",\"$area\","
			#."\"$status\",\"$lastT\",\"$nb_games\"".")");
				die "probleme classement de $trig...\n" 	
					if (!$rows);
			}
		}
	}
	close (H);
}

sub majTournaments2 {

#+------------+-------------------------+------+-----+---------+-------+
#| Field      | Type                    | Null | Key | Default | Extra |
#+------------+-------------------------+------+-----+---------+-------+
#| t_name     | varchar(20)             | NO   |     | NULL    |       | 
#| t_short    | varchar(8)              | NO   | PRI | NULL    |       | 
#| t_style    | enum('Strict','Loose')  | YES  |     | NULL    |       | 
#| t_complete | enum('yes','no')        | YES  |     | NULL    |       | 
#| t_mode     | enum('FtF','Vasl')      | YES  |     | NULL    |       | 
#| t_circuit  | enum('eu','us','other') | YES  |     | NULL    |       | 
#| t_deb      | date                    | YES  |     | NULL    |       | 
#| t_fin      | date                    | YES  |     | NULL    |       | 
#| t_first    | varchar(3)              | YES  |     | NULL    |       | 
#| t_second   | varchar(3)              | YES  |     | NULL    |       | 
#| t_third    | varchar(3)              | YES  |     | NULL    |       | 
#| t_edition  | tinyint(4)              | YES  |     | NULL    |       | 
#| t_city     | varchar(20)             | YES  |     | NULL    |       | 
#| t_country  | varchar(20)             | YES  |     | NULL    |       | 
#+------------+-------------------------+------+-----+---------+-------+

	open(H,"DBase/t2_tourneys.db") or die "$! fichier introuvable";

	<H>;
	LINE2: while (<H>) {
		chomp();
		next LINE2 if /^#/;
		$ligne=$_;
print $ligne,"\n";
		($t_name,$t_short,$t_style,$t_complete,$t_mode,$t_circuit,
			$t_deb,$t_fin,
			$t_first,$t_second,$t_third,
			$t_edition,$t_city,$t_country)=split(/,/);
		$t_edition = 0 unless $t_edition;
		print "$t_edition \n";



		$sth=$dbh->prepare(
		qq(SELECT t_name FROM t2_tourneys WHERE t_short=$t_short) );
		$sth->execute();
		if ($sth->fetchrow_array()) {
			print "Le tournoi ($t_short) existe deja...\n";
		} else {
			print "on cree les references du tournoi $t_short (table t2_tourneys)\n";
			$rows = $dbh->do(
				qq(INSERT INTO t2_tourneys  (t_name,t_short,t_style,t_complete,t_mode,t_circuit,t_deb,t_fin,t_first,t_second,t_third,t_edition,t_city,t_country) VALUES ( $ligne )));
			die "probleme tournoi $t_name, ed:$t_edition...\n" 	
				if (!$rows);
		}
	}
	close (H);
}

sub majTournaments {
	open(H,"DBase/tournaments.db") or die "$! fichier introuvable";

	while (<H>) {
		chomp();
		$ligne=$_;
		($t_name,$t_short,$t_style,$t_circuit,$t_year,$t_month,$t_day,
			$t_edition,$t_city,$t_country)=split(/,/);
		$sth=$dbh->prepare(
		qq(SELECT t_name FROM tournaments WHERE t_short=$t_short) );
		$sth->execute();
		if ($sth->fetchrow_array()) {
			print "Le tournoi ($t_short) existe deja...\n";
		} else {
			print "on cree les references du tournoi $t_short\n";
			$rows = $dbh->do('INSERT INTO tournaments ' 
			.'(t_name,t_short,t_style,t_circuit'
			.',t_year,t_month,t_day'
			.',t_edition,t_city,t_country)'
				.' VALUES ('.$ligne.')');
			die "probleme tournoi $t_name, ed:$t_edition...\n" 	
				if (!$rows);
		}
	}
	close (H);
}

sub majGames {
	$t_short = shift;
	my $max_rd=0, $new=0;
	open (RES,"DBase/".$t_short.".db") 
		or die "$! le fichier $t_short.db n'a pu etre lu";

	while (<RES>) {
		chomp();
		@res=split(/,/);
		$f = lc $res[0];
	# on verifie que le TRIG existe
		$sth=$dbh->prepare(
			qq(SELECT trig FROM players WHERE trig=\"$f\"));
		$sth->execute();
		@ary = $sth->fetchrow_array();
		if (!@ary) {
			die "@ary Le joueur au trig $f n'existe pas...\n";
		} 

		$t_short = $res[1];

	# on remplit le tableau des parties du joueur une a une
		$max_rd= @res-2 if (@res-2 > $max_rd);
		for $round (1..(@res-2)) {
			($s,$r) = ($res[$round+1] =~ /(\w\w\w)(\+|-|=)/);
			$s = lc $s;
			$r='W' if ($r eq '+');
			$r='L' if ($r eq '-');
			$r='T' if ($r eq '=');
			$sym = 'L' if ($r eq 'W');
			$sym = 'W' if ($r eq 'L');
			$sym = 'T' if ($r eq 'T');
			$tab = "ronde".$round;
			if ($f lt $s and $s ne '') {
				$res = $f.$s.$r;
				$$tab{$res}++;
				$sth=$dbh->prepare(
		qq(SELECT t_short FROM t_games WHERE 
				t_short=\"$t_short\"  AND
				round=\"$round\" AND
				f_trig=\"$f\" AND
				s_trig=\"$s\" AND
				result=\"$r\"
				));
				$sth->execute();
				if ($sth->fetchrow_array()) {
					print "Le match ($f,$s,$r) du tournoi $t_short rounde $round existe deja...\n";
				} else {
					$new++;
					$new_game = "INSERT INTO t_games ".
		"(t_short,round, f_trig,s_trig,result) ".
		"VALUES('$t_short','$round','$f','$s','$r')";
					$sth=$dbh->prepare($new_game);
					$sth->execute();
					print "Le match ($f,$s,$r) du tournoi $t_short rounde $round a ete ajoute...\n";
				}
			} elsif ( $s ne '') {
				$res = $s.$f.$sym;
				$$tab{$res}++;
			}

		}
	}
	close (RES);
	print "nous avons rajouté $new parties pour le tournoi $t_short\n";

	
print "verif : max_r vaut : $max_rd\n";	
	# on verifie bien la saisie double f,s et s,f
	foreach $rd (1..$max_rd) {
		$tab = "ronde".$rd;
		foreach $res (keys %{$tab}) {
			if ($$tab{$res}!=2) {
				($f,$s,$r) = ($res =~ /(\w{3})(\w{3})(\w)/);
				print "pb : d un cote $f et $s et d aute\n";
				print " $res : pb de symetrie \n" if ($$tab{$res}!=2);
			}
		}
	}
	
}

sub majGames2 {
	$tourney = uc (shift);
	my $new=0;
	open (RES,"DBase/tblGames_".$tourney.".db") 
		or die "$! le fichier tblGames_$tourney.db n'a pu etre lu";

#1997-10-10,ASLOK_97,0    ,ana  ,hlr   ,W       ,axis  ,attacker,RB6      ,Turned Away
#BN : date ,t_short ,round,first,second,f_result,f_side,role    ,scen_code, scen_name

#2000-02-25,SCO_00  ,c    ,1     ,m FALK    ,k MALMSTROM,FKM   ,MMK    ,W        ,axis  ,WCW 7
#BN : date ,t_short , mode, round,full first,full second,f_trig, s trig, f_result,f_side, scen_code, balance
#     0    ,1       ,2    ,3     ,4         ,5          ,6     ,7      ,8        ,9     ,10        ,11
	while (<RES>) {
		chomp();
		@res=split(/,/);
		$date = $res[0];
		$t_short = uc ($res[1]);
		$mode = $res[2];
		$rd = $res[3];
		$f = uc ($res[6]);
		$s = uc ($res[7]);
		$r = uc ($res[8]);
		$symr="L" if ($r eq "W");
		$symr="W" if ($r eq "L");
		$symr="T" if ($r eq "T");
		if ($res[9] ne "") {
			$side = lc ($res[9]);
		} else {
			$side = "other";
		}
		$scen = uc ($res[10]);
		$sth=$dbh->prepare(
			qq(SELECT axis_side,allied_side,defenders
				FROM scenarios
				WHERE
                        code = "$scen"
                        OR id = "$scen"
			));
		$sth->execute();
		if (@ary=$sth->fetchrow_array()) {
			if ( uc $ary[2] eq uc $ary[0] ) {
				$axis = "defender";
				$allies = "attacker";
			} elsif ( uc $ary[2] eq uc $ary[1] ) {
				$axis = "attacker";
				$allies = "defender";
			} elsif ( lc $ary[2] eq "neither" ) {
				$axis = "neither";
				$allies = "neither";
			} else {
				$axis = "unknown";
				$allies = "unknown";
				$unknown = "unknown";
			}
		}
		$role = ${$side}; 


	# on verifie que les joueurs 1 et 2 existent
		foreach $j (($f,$s)) {
			$sth=$dbh->prepare(
			qq(SELECT trig, name FROM players WHERE trig="$j"));
			$sth->execute();
			@ary = $sth->fetchrow_array();
			if (!@ary) {
				die "@ary Le joueur au trig $j n'existe pas...\n";
			} else {
				$name{$j} = $ary[1];
			}
		}
	# on verifie que la partie n'est pas deja enregistree

		$sth=$dbh->prepare(
		qq(SELECT t_short FROM t2_games WHERE
			t_short=\"$t_short\"  AND
 			round=\"$rd\" AND
 			date=\"$date\" AND
			scen_short=\"$scen\" AND
			( (f_trig=\"$f\" AND
			   s_trig=\"$s\" AND
			   result=\"$r\")   OR
			  (s_trig=\"$f\" AND
			   f_trig=\"$s\" AND
			   result=\"$symr\")
			)
		));
		$sth->execute();
		if ($sth->fetchrow_array()) {
			print "$t_short : $name{$f}($f) $r $name{$s}($s) seems to appear twice... (scenario $scen)\n";
		} else {

			$new++;
print "deb : f side $side et f role $role,\n";
			$role="Unkwown" unless $role;
			$side="Other" unless $side;
print "fin : f side $side et f role $role,\n";
			$new_game = qq( INSERT INTO t2_games 
			 (t_short,mode,round, date, f_trig,s_trig,result, f_side, f_role, scen_short)
			 VALUES('$t_short','$mode','$rd','$date','$f','$s','$r','$side','$role','$scen')
			);
			$sth=$dbh->prepare($new_game);
			$sth->execute();
			print "Le match ($f,$s,$r) du tournoi $t_short rounde $rd a ete ajoute...\n";
		}
	}
	close(RES);
	print "nous avons rajouté $new parties pour le tournoi $t_short\n";
}	
