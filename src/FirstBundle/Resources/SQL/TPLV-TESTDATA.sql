-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 04, 2016 at 07:27 PM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 7.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

--
-- Dumping data for table `field`
--

INSERT INTO `field` (`id`, `workset_id`, `name`, `color`, `order_custom`) VALUES
(4, 1, 'AEM', '#dfe1e2', NULL),
(5, 1, 'Obstétrique', '#e2369f', NULL),
(6, 1, 'Gynéco', '#f3aaed', NULL),
(7, 1, 'Pédiatrie', '#47db1a', NULL),
(8, 1, 'Psychiatrie', '#c7911f', NULL),
(9, 1, 'Ophtalmologie', '#fa5500', NULL),
(10, 1, 'ORL', '#bc7cda', NULL);

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id`, `field_id`, `name`, `number`) VALUES
(10, 4, 'Relation médecin/malade, Annonce d\'une maladie grave', 1),
(11, 4, 'Valeurs professionnelles du médecin', 2),
(12, 4, 'Le raisonnement et la décision, Evidence Based Medecine', 3),
(13, 4, 'Sécurité du patient, Evènements Indésirables Associés aux Soins', 4),
(14, 4, 'Gestion des erreurs et des plaintes, Aléa thérapeutique', 5),
(15, 4, 'Organisation de l\'exercice clinique et méthodes sécurisant le parcours du patient', 6),
(16, 4, 'Droits individuels et collectifs du patient', 7),
(17, 4, 'Ethique médicale', 8),
(18, 4, 'Certificats médicaux – Décès – Prélèvements d\'organes', 9),
(19, 4, 'Violences sexuelles', 10),
(20, 4, 'Soins psychiatriques sans consentement', 11),
(21, 4, 'Responsabilités médicale pénale, civile, administrative et disciplinaire', 12),
(22, 4, 'Démarche qualité et évaluation des professionnels', 13),
(23, 4, 'Formation tout au long de la vie – Analyse critique d\'une information scientifique et médicale – Gestion des liens d\'intérêts', 14),
(24, 4, 'Organisation du parcous de soin – Régulation – Indicateurs', 15),
(25, 4, 'Sécurité sociale, CMU, assurances complémentaires – Consommation médicale et économie de la santé', 16),
(26, 4, 'Le système conventionnel', 17),
(27, 4, 'Méthodologie de la recherche expérimentale et clinique', 18),
(28, 4, 'Mesure de l\'état de santé de la population', 19),
(29, 4, 'Interprétation d\'un enquête épidémiologique', 20),
(30, 4, 'Sujets en situation de précarité', 57),
(31, 4, 'Surveillance des maladies infectieuses transmissibles', 142),
(32, 4, 'Risques émergents, bioterrorisme, maladies hautement transmissibles', 174),
(33, 4, 'Risques sanitaires liés à l\'eau et à l\'alimentation', 175),
(34, 4, 'Risques sanitaires liés aux irradiations – Radioprotection', 176),
(35, 4, 'Sécurité sanitaire des produits destinés à l\'homme', 177),
(36, 4, 'Environnement professionnel et santé en travail', 178),
(37, 4, 'Organisation de la médecine du travail – Prévention des risques professionnels', 179),
(38, 4, 'Accidents du travail et maladies professionnelles', 180),
(39, 4, 'Principe de bon usage du médicament', 318),
(40, 4, 'Décision thérapeutique personnalisée', 319),
(41, 4, 'Analyse critique des études cliniques dans la perspective du bon usage du médicament', 320),
(42, 4, 'Education thérapeutique, observance, automédication', 321),
(43, 4, 'Identification et gestion des risques liés aux médicaments', 322),
(44, 4, 'Cadre réglementaire de la prescription thérapeutique~', 323),
(45, 5, 'Examen pré-nuptial', 21),
(46, 5, 'Grossesse normale', 22),
(47, 5, 'Principales complications de la grossesse', 23),
(48, 5, 'Grossesse extra-utérine', 24),
(49, 5, 'Douleur abdominale aiguë chez une femme enceinte', 25),
(50, 5, 'Prévention des risques foetaux : infection, médicament, toxiques, irradiation', 26),
(51, 5, 'Infection urinaire au cours de la grossesse', 27),
(52, 5, 'Risques professionnels pour la maternité', 28),
(53, 5, 'Prématurité et RCIU', 29),
(54, 5, 'Accouchement, délivrance et suites de couches normales', 30),
(55, 5, 'Allaitement maternel', 32),
(56, 5, 'Suites de couches pathologiques : pathologie maternelle dans les 40 jours', 33),
(57, 5, 'Stérilité du couple : 1ère consultation', 37),
(58, 5, 'Assistance médicale à la procréation', 38),
(59, 5, 'Troubles psychiques de la grossesse et du post-partum', 67),
(60, 5, 'Nutrition et grossesse', 252),
(61, 5, 'Prise en charge d\'une patiente atteinte de pré-éclampsie', 339),
(62, 6, 'Anomalies du cycle menstruel, Métrorragies', 34),
(63, 6, 'Contraception', 35),
(64, 6, 'Interruption Volontaire de Grossesse', 36),
(65, 6, 'Algies pelviennes chez la femme', 39),
(66, 6, 'Aménorrhée', 40),
(67, 6, 'Hémorragie génitale chez la femme', 41),
(68, 6, 'Tuméfaction pelvienne chez la femme', 42),
(69, 6, 'Ménopause et andropause', 120),
(70, 6, 'Tumeurs du col utérin, du corps utérin', 297),
(71, 6, 'Tumeurs de l\'ovaire', 303),
(72, 4, 'Tumeurs du sein', 309),
(73, 7, 'Evaluation et soins du nouveau-né à terme', 31),
(74, 7, 'Problèmes posés par les maladies génétiques', 43),
(75, 7, 'Suivi d\'un nourrisson, enfant et adolescent normal – Dépistage anomalies orthopédiques, auditives et visuelles – Médecine scolaire', 44),
(76, 7, 'Alimentation et besoins nutritionnels du nourrisson et de l\'enfant', 45),
(77, 7, 'Développement bucco-dentaire et anomalies', 46),
(78, 7, 'Puberté normale et pathologique', 47),
(79, 7, 'Troubles de la miction chez l\'enfant', 49),
(80, 7, 'Strabisme chez l\'enfant', 50),
(81, 7, 'Retard de croissance staturo-pondéral', 51),
(82, 7, 'Boiterie chez l\'enfant', 52),
(83, 7, 'Développement psycho-moteur du nourrisson et de l\'enfant', 53),
(84, 7, 'L\'enfant handicapé', 54),
(85, 7, 'Maltraitance et enfant en danger', 55),
(86, 7, 'Troubles du sommeil de l\'enfant et de l\'adulte', 108),
(87, 7, 'Douleur de l\'enfant', 134),
(88, 7, 'Soins palliatifs en pédiatrie', 139),
(89, 7, 'Vaccinations', 143),
(90, 7, 'Fièvre aiguë chez l\'enfant', 144),
(91, 7, 'Déficit immunitaire', 185),
(92, 7, 'RGO du nourrisson', 268),
(93, 7, 'Vomissements du nourrisson', 271),
(94, 7, 'Constipation chez l\'enfant', 280),
(95, 7, 'Diarrhée aiguë et déshydratation', 283),
(96, 7, 'Cancers de l\'enfant', 294),
(97, 7, 'Malaise grave du nourrisson et mort subite', 340),
(98, 7, 'Convulsions chez le nourrisson et l\'enfant', 341),
(99, 7, 'DRA du nouveau-né', 354),
(100, 8, 'Sexualité normale et ses troubles', 56),
(101, 8, 'Facteurs de risques, Prévention et Dépistage des troubles psychiques de l\'enfant à la personne âgée', 58),
(102, 8, 'Classification des troubles mentaux de l\'enfant à la personne âgée', 59),
(103, 8, 'Organisation de l\'offre de soins en psychiatrie', 60),
(104, 8, 'Trouble schizophrénique de l\'adolescent et de l\'adulte', 61),
(105, 8, 'Trouble bipolaire de l\'adolescent et de l\'adulte', 62),
(106, 8, 'Trouble délirant persistant', 63),
(107, 8, 'Diagnostiquer : trouble dépressif, trouble anxieux généralisé, trouble de panique, trouble phobique, TOC, état de stress post-traumatique, trouble de l\'adaptation, trouble de la personnalité', 64),
(108, 8, 'Troubles envahissants du développement', 65),
(109, 8, 'Troubles du comportement de l\'adolescent', 66),
(110, 8, 'Troubles somatoformes à tous âges', 70),
(111, 8, 'Psychothérapies', 71),
(112, 8, 'Prescription de psychotropes (voir item 326)', 72),
(113, 8, 'Addiction comportementales', 77),
(114, 8, 'Dopage', 78),
(115, 8, 'Douleur en santé mentale', 135),
(116, 8, 'Deuil normal et pathologique', 141),
(117, 8, 'Agitation et délire aigu', 346),
(118, 8, 'Crise d\'angoisse aiguë et attaque de panique', 347),
(119, 8, 'Risque et conduite suicidaire chez l\'enfant, l\'adolescent et l\'adulte', 348),
(120, 9, 'Altération de la fonction visuelle', 79),
(121, 9, 'Anomalies de la vision d\'apparition brutale', 80),
(122, 9, 'Oeil rouge et/ou douloureux', 81),
(123, 9, 'Glaucome chronique', 82),
(124, 9, 'Troubles de la réfraction', 83),
(125, 9, 'Pathologies des paupières', 84),
(126, 9, 'Diplopie', 100),
(127, 10, 'pistaxis', 85),
(128, 10, 'Trouble aigu de la parole – Dysphonie', 86),
(129, 10, 'Altération de la fonction auditive', 87),
(130, 10, 'Pathologies des glandes salivaires', 88),
(131, 10, 'Infections naso-sinusiennes de l\'enfant et de l\'adulte', 145),
(132, 10, 'Angines et rhinipharyngites', 146),
(133, 10, 'Otites infectieuses', 147),
(134, 10, 'Tumeurs de la cavité buccale, naso-sinusienne ou du cavum, ou des VADS', 295);

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `surname`, `login`, `password`) VALUES
(1, 'Sophie', 'MouAmoureuse', 'sophie', 'wxcvbn');

--
-- Dumping data for table `workset`
--

INSERT INTO `workset` (`id`, `name`, `description`, `generic`) VALUES
(1, 'Médecine', 'Révision du programme de Médecine pour l\'ECN (portant sur D2, D3 & D4)', 1),
(2, 'ZOB', 'ZOB', 0);

