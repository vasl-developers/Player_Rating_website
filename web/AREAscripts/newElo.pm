#!/usr/bin/perl

use warnings;
use strict;
use POSIX qw(strftime);
use Time::localtime;
use Date::Calc qw(Date_to_Days);
use Locale::Country;

use DBI;
use Encode qw(encode decode);
my $enc='utf-8';


$majdate  = strftime " %e %b %Y", gmtime;

$to_day=Date_to_Days("1999","12","31");

$tm = localtime;
$cur_y = $tm->year + 1900;
#$cur_m = $tm->month;
#$cur_d = $tm->day;
#print "on a $cur_y , $cur_m and $cur_d \n";exit();

#-----------------------------------------------------
# 1. Ouvrir les bases et effacer les tables
#-----------------------------------------------------
# on se connecte à la base de données
my ($dsn) = "DBI:mysql:area:localhost";
my ($username) = "root";
my ($password) = "enunlugar";
my ($dbh, $sth, $sthbis);
my (@ary);

$dbh = DBI->connect($dsn, $username, $password, {RaiseError => 1});
# on efface les lignes de la table delta
$sth = $dbh->prepare("delete from latest");
$sth->execute();
# on efface les lignes de la table evolution
$sth = $dbh->prepare("delete from evolution");
$sth->execute();

#-----------------------------------------------------
# 2. Initialisation des joueurs
#-----------------------------------------------------
# on charge tous les trigrammes des joueurs enregistrés
$sth = $dbh->prepare("SELECT trig,name,first_n,diminutif,country FROM players");
$sth->execute();

print "On demarre l initialisation des joueurs...\n";
$i = 0;
while (@ary = $sth->fetchrow_array() ) {

	$i++;
	$trig = uc $ary[0];
	$nom{$trig} = encode($enc, uc $ary[1]);
	$prenom{$trig} = encode($enc, $ary[2]);
	if (lc $ary[3] ne "nil") {
		$diminutif{$trig} = encode($enc, $ary[3]);
	} else {
		$diminutif{$trig} = encode($enc, $ary[2]);
	}
	$pays{$trig} = $ary[4];
	$actif{$trig} = "no";
	$provisional{$trig} = "yes";

	$hwm{$trig} = 0;
	$elo{$trig} = 1500;

	$jouees{$trig} = 0;
	$gagnees{$trig} = 0;
	$joueesAtt{$trig} = 0;
	$gagneesAtt{$trig} = 0;
	$joueesDef{$trig} = 0;
	$gagneesDef{$trig} = 0;
	$joueesAxe{$trig} = 0;
	$gagneesAxe{$trig} = 0;
	$joueesAllies{$trig} = 0;
	$gagneesAllies{$trig} = 0;
	$streak{$trig} = 0;
	
}

open(I,"init_elo.csv");
while (<I>) {
	chomp;
	($trig,$elo)=split(/,/);
	$elo{uc $trig} = $elo;
	$hwm{uc $trig} = $elo;
}
close(I);

#pour zoomer sur un gars donne
$gars="HYA";
$paselo=$elo{$gars};


print "... fin : on a $i joueurs \n";

#-----------------------------------------------------
# a. Liste ordonnée des jours de match
#-----------------------------------------------------

print "On va lister les jours : \n";

		## ???  ²$joueesAxe{$s_trig} ++ if ($s_side eq "Axis");
$sth = $dbh->prepare(
	qq/SELECT date,count(*) FROM t2_games GROUP BY date
		ORDER BY date
	/); 
$sth->execute();
while (@ary = $sth->fetchrow_array() ) {
	push @jours, $ary[0];	
}

print "... nous avons $#jours jours\n";

#-----------------------------------------------------
# b. POUR chaque jour de match ....
#-----------------------------------------------------

$nbjour=0;
foreach $date (@jours) {

	$sth = $dbh->prepare(
	qq/SELECT f_trig, s_trig, 
		 	result, f_side, f_role , 
		 	t_short, round, date, scen_short
		FROM t2_games
		WHERE date="$date"
		AND (mode="wc" OR mode="c"  
			OR mode="open" OR mode="o" 
			OR mode="grofaz" OR mode="g" 
			OR mode="mini" OR mode="m" 
			OR mode like "optin" )
		ORDER BY round
	/);
	$sth->execute();
	while (@ary = $sth->fetchrow_array() ) {
		($f_trig, $s_trig, 
		$res, $f_side, $f_role , 
		$t_short, $round, $date, $scen_short) = @ary;
		@ligne_f = ($f_trig,$s_trig,
			$res, $f_side, $f_role , 
			$t_short, $round, $date, $scen_short);
		@ligne_s = ($s_trig,$f_trig, 
			symres($res), symside($f_side), symrole($f_role), 
			$t_short, $round, $date, $scen_short);
if (symres($res) eq "pepin") {print "$f_trig,$s_trig, $t_short, $round\n";}

		$first{$f_trig} =$date unless ($first{$f_trig});
		$first{$s_trig} =$date unless ($first{$s_trig});
		$last{$f_trig} =$date;
		$last{$s_trig} =$date;
if ($date eq "0000-00-00") {print ": $f_trig , $s_trig $t_short, $round \n"; exit}

		$jouees{$f_trig} ++;
		$jouees{$s_trig} ++;
		$provisional{$f_trig} = "no" if ($jouees{$f_trig} > 10) ;
		$provisional{$s_trig} = "no" if ($jouees{$s_trig} > 10) ;
		$joueesAtt{$f_trig} ++ if ($f_role eq "Attacker");
		$joueesDef{$f_trig} ++ if ($f_role eq "Defender");
		$joueesAxe{$f_trig} ++ if ($f_side eq "Axis");
		$joueesAllies{$f_trig} ++ if ($f_side eq "Allies");
		$joueesAtt{$s_trig} ++ if ($s_role eq "Attacker");
		$joueesDef{$s_trig} ++ if ($s_role eq "Defender");
		$joueesAxe{$s_trig} ++ if ($s_side eq "Axis");
		$joueesAllies{$s_trig} ++ if ($s_side eq "Allies");
		if (uc $res eq "W") {
			$fw = 1;
			$sw = 0;
			$gagnees{$f_trig} ++;
			$gagneesAtt{$f_trig} ++ if ($f_role eq "Attacker");
			$gagneesDef{$f_trig} ++ if ($f_role eq "Defender");
			$gagneesAxe{$f_trig} ++ if ($f_side eq "Axis");
			$gagneesAllies{$f_trig} ++ if ($f_side eq "Allies");
			$streak{$f_trig} ++;
			$highestStreak{$f_trig}=$streak{$f_trig} 
				if ($highestStreak{$f_trig} < $streak{$f_trig});
			$highestStreak{$s_trig}=$streak{$s_trig} 
				if ($highestStreak{$s_trig} < $streak{$s_trig});
			$streak{$s_trig}=0;
		} elsif (uc $res eq "L") {
			$fw = 0;
			$sw = 1;
			$gagnees{$s_trig} ++;
			$gagneesAtt{$s_trig} ++ if ($s_role eq "Attacker");
			$gagneesDef{$s_trig} ++ if ($s_role eq "Defender");
			$gagneesAxe{$s_trig} ++ if ($s_side eq "Axis");
			$gagneesAllies{$s_trig} ++ if ($s_side eq "Allies");
			$streak{$s_trig}++;
			$highestStreak{$f_trig}=$streak{$f_trig} 
				if ($highestStreak{$f_trig} < $streak{$f_trig});
			$highestStreak{$s_trig}=$streak{$s_trig} 
				if ($highestStreak{$s_trig} < $streak{$s_trig});
			$streak{$f_trig}=0;
		} elsif (uc $res eq "T") {
			$fw = 0.5;
			$sw = 0.5;
			$streak{$s_trig}++;
			$streak{$f_trig}++;
			$highestStreak{$f_trig}=$streak{$f_trig} 
				if ($highestStreak{$f_trig} < $streak{$f_trig});
			$highestStreak{$s_trig}=$streak{$s_trig} 
				if ($highestStreak{$s_trig} < $streak{$s_trig});
		}
		$dfs = ($elo{$f_trig} - $elo{$s_trig})/400;
		$dsf = ($elo{$s_trig} - $elo{$f_trig})/400;
		$fwe = 1/(1 + 10**$dsf);
		$swe = 1/(1 + 10**$dfs);
		
		$FactorK = factor_k($elo{$f_trig},$jouees{$f_trig});
		$upf = $FactorK * ($fw - $fwe);

		$FactorK = factor_k($elo{$s_trig},$jouees{$s_trig});
		$ups = $FactorK * ($sw - $swe);

		push @ligne_f, int($upf * 10)/10;
		push @ligne_s, int($ups * 10)/10;
		$boutabout_f = "\"".join("\",\"",@ligne_f)."\"";
		$boutabout_s = "\"".join("\",\"",@ligne_s)."\"";

if ($f_trig eq $gars) { $paselo+=$ligne_f[9];print "game vs ",$ligne_f[1]," points lost/won : ", $ligne_f[9]," (total : $paselo)\n"};
if ($s_trig eq $gars) { $paselo+=$ligne_s[9];print "game vs ",$ligne_s[1]," points lost/won : ", $ligne_s[9]," (total : $paselo)\n"};
		$sthbis = $dbh->prepare(
			qq/INSERT INTO evolution 
	(trig1,trig2,result,side,role,t_short,round,date,scen_short,delta_elo) 
	VALUES ($boutabout_f )/);
		$sthbis->execute();
		$sthbis = $dbh->prepare(
			qq/INSERT INTO evolution 
	(trig1,trig2,result,side,role,t_short,round,date,scen_short,delta_elo) 
	VALUES ($boutabout_s ) /);
		$sthbis->execute();

		$delta{$f_trig} += $upf;
		$delta{$s_trig} += $ups;

	}
	print "on a fini le jour ($date) : ",$nbjour++,"\n";
#a la fin de la journee on fait un upgrade du classement
	foreach $t (keys %delta) {
		$elo{$t} += $delta{$t};
##print "on a : elo($t) : ",$elo{$t},"\n";
		$hwm{$t} = $elo{$t} if ($hwm{$t}< $elo{$t});
		delete ($delta{$t});
	}
}

#a la fin du dernier jour, on met à jour l etat courant elo/hwm

foreach $t (keys %last) {

	$elo=int($elo{$t}*10)/10;
	$hwm=int($hwm{$t}*10)/10;
	($yyyy, $mm, $dd) = ($last{$t} =~ /(\d+)-(\d+)-(\d+)/ );
print "($yyyy, $mm, $dd) avant\n";
	$tfin=Date_to_Days($yyyy, $mm, $dd);
	$depuis = $to_day - $tfin;
print "on a $tfin ($yyyy, $mm, $dd) et (depuis $depuis)\n";
	if ($depuis < 800) {
		$actif="yes";
	} else {
		$actif="no";
	}

	@ligne_latest=(
		$t,$nom{$t},$diminutif{$t},$pays{$t},
		$actif,$provisional{$t},
		$first{$t},$last{$t},
		$hwm,$elo,
		$jouees{$t},$gagnees{$t},
		$joueesAtt{$t},$gagneesAtt{$t},
		$joueesDef{$t},$gagneesDef{$t},
		$joueesAxe{$t},$gagneesAxe{$t},
		$joueesAllies{$t},$gagneesAllies{$t},
		$streak{$t},$highestStreak{$t}   );
	$boutabout_latest = "\"".join("\",\"",@ligne_latest)."\"";

	$sth = $dbh->prepare(
			qq/INSERT INTO latest
		( trig,name,first_n,country,
       	        	active,provisional,
                	first,last,
                	hwm,elo,
                	jouees,gagnees,
                	joueesAtt,gagneesAtt,
                	joueesDef,gagneesDef,
                	joueesAxis,gagneesAxis,
                	joueesAllies,gagneesAllies,
                	curstreak,histreak )
		VALUES ($boutabout_latest ) /);
	$sth->execute();
}



#==========SOUS ROUTINES===============================
# elo : r+=k*(w-we) 
# 16 à 32
# k v de 40 a 10 par pas de 10 selon le rating
# 0<1800 40, 1800<2000 30, 2000<2200 20 , et 10 au-dela
# w vaut 1 pour gain, 0,5 pour tie et 0 pour loose
# we vaut 1/(10**((R2-R1)/400) + 1)
#

sub factor_k {
	#my $k1 = 60; $k2 = 40; $k3 = 20;
	#my $elo = shift;
	#my $games = shift;
	#return ($k1) if $games < 30;
	#return ($k1) if $games < 10;
	#return ($k2) if $elo < 1799;
	#return ($k3);
#vesion FIDE
	#my $k1 = 25; $k2 = 15; $k3 = 10;
	#my $elo = shift;
	#my $games = shift;
	#return ($k1) if $games < 30;
	#return ($k2) if $elo < 2400;
	#return ($k3);
#vesion ASO (Bjarne Marell)
	my $k0 = 50; my $k1 = 40; my $k2 = 30; my $k3 = 20; my $k4 = 10;
	my $elo = shift;
	my $games = shift;
	return ($k0) if $games < 10;
	return ($k1) if $elo < 1800;
	return ($k2) if $elo < 2000;
	return ($k3) if $elo < 2200;
	return ($k4);
}

sub by_elo {
	if ($elo{$a} < $elo{$b}) {
		return 1;
	} elsif ($elo{$a} == $elo{$b}) {
		return 0;
	}  elsif ($elo{$a} > $elo{$b}) {
		return -1;
	}
}

sub symres {
	my $r, $s;
	$r = shift;
	if (uc $r eq "W") {
		$s="L";
	} elsif (uc $r eq "L") { 
		$s="W";
	} elsif (uc $r eq "T") { 
		$s="T";
	} else {
		print "le résultat ($r, $s) n est ni w ni l ni t...\n";
		$s="pepin";
	}
	return ($s);
}

sub symside {
	my $r, $s;
	$r = shift;
	if (uc $r eq "AXIS") {
		$s="Allies";
	} elsif (uc $r eq "ALLIES") { 
		$s="Axis";
	} else {
		$s="";
	}
	return ($s);
}

sub symrole {
	my $r, $s;
	$r = shift;
	if (uc $r eq "DEFENDER") {
		$s="Attacker";
	} elsif (uc $r eq "ATTACKER") { 
		$s="Defender";
	} else {
		$s="";
	}
	return ($s);
}

