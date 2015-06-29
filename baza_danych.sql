-- phpMyAdmin SQL Dump
-- version 4.4.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Czas generowania: 29 Cze 2015, 03:53
-- Wersja serwera: 5.6.24
-- Wersja PHP: 5.5.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Baza danych: `kam_2`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(10) unsigned NOT NULL,
  `username` varchar(30) NOT NULL,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `address` varchar(45) NOT NULL,
  `password` char(32) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`user_id`, `username`, `first_name`, `last_name`, `address`, `password`) VALUES
(2, 'b2', 'Jakubasdasdas', 'bsdf', 'Lipinki293', '7a1e76f1b857fce47538eb794d73ae67'),
(4, 'b3', 'Jakubaaasdasdasd', 'Brzegowskiasd', 'Lipinki 293', '7a1e76f1b857fce47538eb794d73ae67'),
(5, 'b6', 'Jakub', 'Brzegowski', 'Lipinki 293', '7a1e76f1b857fce47538eb794d73ae67'),
(6, 'b7', 'Jakubasdfsadf', 'Brzegowskisdf', 'Lipinki 293', '7a1e76f1b857fce47538eb794d73ae67'),
(7, 'b11', 'Jakubb', 'Brzegowskii', 'sdfasfd', '7a1e76f1b857fce47538eb794d73ae67');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
