#!/usr/bin/perl
use strict;
use warnings;
use DBI;
use Time::Local;
use Date::Calc qw(Week_Number);

$c2 = 1200 ;
$q = 0,0057565;
$pi = 3,141516;
#see glicko

$gRD = 1/(sqrt(1+3*(0.0057565**2)*$RD**2/3.1416));
print "gdR vaut : $gRD\n";
if ($ARGV[0] eq "-t") {
	$track = uc ($ARGV[1]);
} else {
	$track = 'BXP';
}
$file=$track."\.par";
#voici les fichiers ouverts pour r�sultat
open(P,">$file") or die "$! pas trouv� le fichier $file...\n";
open(R,">newParcours");
open(T,">newTous");
open(A,">newActifs");
open(E,">newEvolution");
open(G,">newGames");

$last_year = 2000;
#

#-----------------------------------------------------
# Initialisation des joueurs
#-----------------------------------------------------
# on se connecte � la base de donn�es
my ($dsn) = "DBI:mysql:area:localhost";
my ($username) = "root";
my ($username) = "root";
my ($password) = "secret";
my ($dbh, $sth);
my (@ary);
$dbh = DBI->connect($dsn, $username, $password, {RaiseError => 1});

# on charge tous les trigrammes des joueurs enregistr�s
$sth = $dbh->prepare("SELECT trig,name,first_n,country FROM players");
$sth->execute();

while (@ary = $sth->fetchrow_array() ) {
	$ary[0] = uc $ary[0];
	$nom{$ary[0]} = uc $ary[1];
	$prenom{$ary[0]} = $ary[2];
	$pays{$ary[0]} = $ary[3];
	$jouees{$ary[0]} = 0;
	$gagnees{$ary[0]} = 0;
	$streak{$ary[0]} = 0;
	$pendant{$ary[0]} = 0;
	$glicko{$ary[0]} = 1500;
	$rd{$ary[0]} = 350;
}

$son_area = $area{$track};

# quelles ann�es sont concern�es ?
$sth = $dbh->prepare(
	qq(	SELECT year(date),count(*) FROM t2_games GROUP BY year(date)
		ORDER by year(date) 
	)); 
$sth->execute();
while (@ary = $sth->fetchrow_array() ) {
	push @years, $ary[0];	
}

# pour chaque ann�e : quelles semaines sont concern�es ?
foreach $y in (@years) {
	$sth = $dbh->prepare(
		qq(SELECT  week(date,1) 
			FROM t2_games
			WHERE year(date)="$y"
			ORDER BY week(date,1)
	));
	$sth->execute();
	while (@ary = $sth->fetchrow_array() ) {
		push @{semaines.$y}, $ary[0];	
	}
}

$indexW = 0;
$semprec = 0;

##POUR chaque ann�e
foreach $y in (@years) {


###POUR chaque semaine
	foreach $s in (@{semaines.$y}) {
print "semaine $s de l'ann�e $y\n";
		$gap = $s - $sprec;
		$indexW += $gap;
		
####POUR chaque joueur
		foreach $j (keys %glicko) {
			$f = 0;
			$s = 0;
			$weeks{$j} += $gap;
			delete ($plays{$j});
			$sth = $dbh->prepare(
				qq(SELECT  count(*)
				FROM t2_games
				WHERE year(date)="$y" AND week(date)="$s" AND f_trig="$j"
				GROUP BY $f_trig
			));
			$sth->execute();
			while (@ary = $sth->fetchrow_array() ) {
				$f = $ary[0];
			}
			$sth = $dbh->prepare(
				qq(SELECT  count(*)
				FROM t2_games
				WHERE year(date)="$y" AND week(date)="$s" AND s_trig="$j"
				GROUP BY $s_trig
			));
			$sth->execute();
			while (@ary = $sth->fetchrow_array() ) {
				$s = $ary[0];
			}
			if ($s + $f > 0 ) {
				$plays{$j} = $s + $f;
				$rd{$j} = sqrt($rd{$j}**2 + $c2 * $weeks{$j});
				$rd{$j} =350 if ($rd{$j}>350);
				$weeks{$j}=0;
			}
		}

####POUR chaque joueur actif sur la semaine
		foreach $j (keys %plays) {
			@res=();
			@opp=();
			@oppRtg=();
			@oppRD=();
			$sth = $dbh->prepare(
				qq(SELECT s_trig,result 
				FROM t2_games
				WHERE year(date)="$y" AND week(date)="$s" AND f_trig="$j"
			));
			$sth->execute();
			while (@ary = $sth->fetchrow_array() ) {
				push @opp, $ary[0];
				if ($ary[1] eq "W") {
					$res = 1;
				} elsif ($ary[1] eq "T") {
					$res = 0.5;
				} elsif ($ary[1] eq "L") {
					$res = 0;
				}
				push @res, $res;
				push @resRtg, $glicko{$ary[0]};
				push @resRD, $rd{$ary[0]};
			}
			$sth = $dbh->prepare(
				qq(SELECT f_trig,result 
				FROM t2_games
				WHERE year(date)="$y" AND week(date)="$s" AND s_trig="$j"
			));
			$sth->execute();
			while (@ary = $sth->fetchrow_array() ) {
				push @opp, $ary[0];
				if ($ary[1] eq "L") {
					$res = 1;
				} elsif ($ary[1] eq "T") {
					$res = 0.5;
				} elsif ($ary[1] eq "W") {
					$res = 0;
				}
				push @res, $res;
				push @resRtg, $glicko{$ary[0]};
				push @resRD, $rd{$ary[0]};
			}

			foreach $opp (0..$#resRtg) {
				$g = gRD(
				 

			 



}


# elo : r+=k*(w-we) 
# k v de 40 a 10 par pas de 10 selon le rating
# 0<1800 40, 1800<2000 30, 2000<2200 20 , et 10 au-dela
# w vaut 1 pour gain, 0,5 pour tie et 0 pour loose
# we vaut 1/(10**((R2-R1)/400) + 1)
#

sub gRD {
	my $var = shift;
	return(1/sqrt(3 * $q**2 * $var**2/$pi + 1));
}
