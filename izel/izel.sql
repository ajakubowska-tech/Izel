-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Wrz 27, 2026 at 09:50 PM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `izel`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `bucket_list`
--

CREATE TABLE `bucket_list` (
  `id` int(11) NOT NULL,
  `couple_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `category` enum('podroze','w-domu','aktywnosci','randki','jedzonko','marzenia','inne') NOT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `completed_by` int(11) DEFAULT NULL,
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `couples`
--

CREATE TABLE `couples` (
  `id` int(11) NOT NULL,
  `couple_name` varchar(100) NOT NULL,
  `pairing_code_hash` varchar(64) NOT NULL,
  `relationship_start_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `background_photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `daily_notes`
--

CREATE TABLE `daily_notes` (
  `id` int(11) NOT NULL,
  `couple_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `note_date` date NOT NULL,
  `content` text NOT NULL,
  `mood` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `important_dates`
--

CREATE TABLE `important_dates` (
  `id` int(11) NOT NULL,
  `couple_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `event_date` date NOT NULL,
  `is_recurring` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `remember_tokens`
--

CREATE TABLE `remember_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token_hash` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `time_capsule_notes`
--

CREATE TABLE `time_capsule_notes` (
  `id` int(11) NOT NULL,
  `couple_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `content` text NOT NULL,
  `unlock_at` datetime NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `couple_id` int(11) NOT NULL,
  `name` varchar(10) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `bucket_list`
--
ALTER TABLE `bucket_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_couple_id` (`couple_id`),
  ADD KEY `idx_author_id` (`author_id`),
  ADD KEY `idx_completed_by` (`completed_by`);

--
-- Indeksy dla tabeli `couples`
--
ALTER TABLE `couples`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `daily_notes`
--
ALTER TABLE `daily_notes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_note_per_day` (`author_id`,`note_date`),
  ADD KEY `idx_couple_id` (`couple_id`),
  ADD KEY `idx_author_id` (`author_id`);

--
-- Indeksy dla tabeli `important_dates`
--
ALTER TABLE `important_dates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_couple_id` (`couple_id`),
  ADD KEY `idx_author_id` (`author_id`);

--
-- Indeksy dla tabeli `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `time_capsule_notes`
--
ALTER TABLE `time_capsule_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_couple_id` (`couple_id`),
  ADD KEY `idx_author_id` (`author_id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_couple_id` (`couple_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bucket_list`
--
ALTER TABLE `bucket_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `couples`
--
ALTER TABLE `couples`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_notes`
--
ALTER TABLE `daily_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `important_dates`
--
ALTER TABLE `important_dates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `time_capsule_notes`
--
ALTER TABLE `time_capsule_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bucket_list`
--
ALTER TABLE `bucket_list`
  ADD CONSTRAINT `bucket_list_ibfk_1` FOREIGN KEY (`couple_id`) REFERENCES `couples` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bucket_list_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bucket_list_ibfk_3` FOREIGN KEY (`completed_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `daily_notes`
--
ALTER TABLE `daily_notes`
  ADD CONSTRAINT `daily_notes_ibfk_1` FOREIGN KEY (`couple_id`) REFERENCES `couples` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `daily_notes_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `daily_notes_ibfk_3` FOREIGN KEY (`couple_id`) REFERENCES `couples` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `daily_notes_ibfk_4` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `important_dates`
--
ALTER TABLE `important_dates`
  ADD CONSTRAINT `important_dates_ibfk_1` FOREIGN KEY (`couple_id`) REFERENCES `couples` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `important_dates_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD CONSTRAINT `remember_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `time_capsule_notes`
--
ALTER TABLE `time_capsule_notes`
  ADD CONSTRAINT `time_capsule_notes_ibfk_1` FOREIGN KEY (`couple_id`) REFERENCES `couples` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `time_capsule_notes_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`couple_id`) REFERENCES `couples` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
