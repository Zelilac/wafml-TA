-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: Nov 26, 2025 at 11:20 AM
-- Server version: 8.0.30
-- PHP Version: 7.4.33


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `evote_ai`
--

-- --------------------------------------------------------

--
-- Table structure for table `ms_mhs`
--

CREATE TABLE `ms_mhs` (
  `nim` varchar(10) NOT NULL,
  `nama` varchar(60) DEFAULT NULL,
  `gelardepan` varchar(20) DEFAULT NULL,
  `gelarbelakang` varchar(20) DEFAULT NULL,
  `kodeunit` varchar(6) NOT NULL,
  `periodedaftar` varchar(6) DEFAULT NULL,
  `program` varchar(5) DEFAULT NULL,
  `sistemkuliah` char(2) NOT NULL,
  `kodeangkatan` varchar(5) DEFAULT NULL,
  `jalurpenerimaan` varchar(10) DEFAULT NULL,
  `gelombang` varchar(10) DEFAULT NULL,
  `sex` char(1) DEFAULT 'L',
  `tmplahir` varchar(50) DEFAULT NULL,
  `tgllahir` date DEFAULT NULL,
  `kodeagama` decimal(2,0) DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `dusun` varchar(100) DEFAULT NULL,
  `rt` varchar(50) DEFAULT NULL,
  `rw` varchar(50) DEFAULT NULL,
  `kelurahan` varchar(100) DEFAULT NULL,
  `kecamatan` varchar(100) DEFAULT NULL,
  `kode_will` varchar(40) DEFAULT NULL,
  `kodekota` varchar(4) DEFAULT NULL,
  `kodeprop` varchar(2) DEFAULT NULL,
  `kodepos` char(5) DEFAULT NULL,
  `telp` varchar(100) DEFAULT NULL,
  `telp2` varchar(15) DEFAULT NULL,
  `hp` varchar(100) DEFAULT NULL,
  `hp2` varchar(15) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `email2` varchar(50) DEFAULT NULL,
  `norekening` varchar(20) DEFAULT NULL,
  `kodependidikan` decimal(2,0) DEFAULT NULL,
  `goldarah` char(2) DEFAULT NULL,
  `warganegara` varchar(10) DEFAULT NULL,
  `statuskerja` decimal(1,0) DEFAULT '0',
  `pekerjaan` varchar(50) DEFAULT NULL,
  `jabatan` varchar(50) DEFAULT NULL,
  `alamatpersh` varchar(100) DEFAULT NULL,
  `jenisinstansi` varchar(10) DEFAULT NULL,
  `namaperusahaan` varchar(60) DEFAULT NULL,
  `telppersh` varchar(50) DEFAULT NULL,
  `penanggungdana` varchar(10) DEFAULT NULL,
  `tglregistrasi` date DEFAULT NULL,
  `nip` varchar(10) DEFAULT NULL,
  `statusmhs` char(1) NOT NULL,
  `bidangstudi` varchar(5) DEFAULT NULL,
  `semmhs` decimal(2,0) DEFAULT NULL,
  `ipk` decimal(3,2) DEFAULT '0.00',
  `skslulus` decimal(3,0) DEFAULT NULL,
  `skslalu` decimal(3,0) DEFAULT NULL,
  `ipslalu` decimal(3,2) DEFAULT '0.00',
  `batassks` decimal(3,0) DEFAULT NULL,
  `cuti` decimal(2,0) DEFAULT '0',
  `sistemspp` decimal(2,0) DEFAULT '0',
  `periodewisuda` decimal(4,0) DEFAULT NULL,
  `noijasah` varchar(50) DEFAULT NULL,
  `tglijasah` date DEFAULT NULL,
  `notranskrip` varchar(50) DEFAULT NULL,
  `tglselesai` date DEFAULT NULL,
  `mhstransfer` decimal(1,0) DEFAULT '0',
  `ptstatusdaftar` varchar(10) DEFAULT NULL,
  `ptnim` varchar(20) DEFAULT NULL,
  `ptthnmasuk` decimal(4,0) DEFAULT NULL,
  `ptasal` varchar(100) DEFAULT NULL,
  `ptjurusan` varchar(100) DEFAULT NULL,
  `ptipk` decimal(3,2) DEFAULT '0.00',
  `ptthnlulus` decimal(4,0) DEFAULT NULL,
  `sksasal` decimal(3,0) DEFAULT NULL,
  `ptsemester` decimal(2,0) DEFAULT NULL,
  `asalsmu` varchar(100) DEFAULT NULL,
  `kotasmu` varchar(15) DEFAULT NULL,
  `alamatsmu` varchar(100) DEFAULT NULL,
  `telpsmu` varchar(30) DEFAULT NULL,
  `propinsismu` varchar(20) DEFAULT NULL,
  `jenissmu` varchar(10) DEFAULT NULL,
  `statussmu` varchar(10) DEFAULT NULL,
  `jurusansmu` varchar(50) DEFAULT NULL,
  `unasmp` decimal(2,0) DEFAULT NULL,
  `unasnilai` decimal(5,2) DEFAULT NULL,
  `sttbmp` decimal(2,0) DEFAULT NULL,
  `sttbnilai` decimal(5,2) DEFAULT NULL,
  `sttbtahun` decimal(4,0) DEFAULT NULL,
  `alasankeluar` varchar(10) DEFAULT NULL,
  `statuspilih` decimal(1,0) DEFAULT '0',
  `statusnikah` decimal(1,0) DEFAULT '0',
  `namaistri` varchar(70) DEFAULT NULL,
  `namaayah` varchar(70) DEFAULT NULL,
  `statusayah` decimal(1,0) DEFAULT NULL,
  `namaibu` varchar(70) DEFAULT NULL,
  `statusibu` decimal(1,0) DEFAULT NULL,
  `pendidikanayah` decimal(2,0) DEFAULT NULL,
  `pendapatanortu` decimal(2,0) DEFAULT NULL,
  `pekerjaanayah` varchar(5) DEFAULT NULL,
  `pendidikanibu` decimal(2,0) DEFAULT NULL,
  `pendapatanibu` decimal(2,0) DEFAULT NULL,
  `pekerjaanibu` varchar(5) DEFAULT NULL,
  `alamatortu` varchar(100) DEFAULT NULL,
  `kotaortu` varchar(15) DEFAULT NULL,
  `kodeposortu` char(5) DEFAULT NULL,
  `telportu` varchar(15) DEFAULT NULL,
  `catatankhusus` varchar(4000) DEFAULT NULL,
  `nimasal` varchar(50) DEFAULT NULL,
  `cekalfakultas` decimal(1,0) DEFAULT '0',
  `cekalkeuangan` decimal(1,0) DEFAULT '0',
  `fileijazah` varchar(50) DEFAULT NULL,
  `t_userid` varchar(10) DEFAULT NULL,
  `t_updatetime` varchar(30) DEFAULT NULL,
  `t_ipaddress` varchar(30) DEFAULT NULL,
  `eps_kodeprop` varchar(2) DEFAULT NULL,
  `eps_kodekab` varchar(2) DEFAULT NULL,
  `eps_kodeptasal` varchar(10) DEFAULT NULL,
  `eps_kodeprodiasal` varchar(10) DEFAULT NULL,
  `eps_noijazahasal` varchar(100) DEFAULT NULL,
  `eps_kodejenjangasal` varchar(2) DEFAULT NULL,
  `eps_sksdiakui` decimal(4,0) DEFAULT NULL,
  `nopendaftaran` varchar(50) DEFAULT NULL,
  `groupkeuangan` varchar(20) DEFAULT NULL,
  `sksasalbar` decimal(3,0) DEFAULT NULL,
  `sisamasastudi` decimal(2,0) DEFAULT NULL,
  `makulsisa` decimal(3,0) DEFAULT NULL,
  `id_pd` varchar(50) DEFAULT NULL,
  `id_reg_pd` varchar(50) DEFAULT NULL,
  `id_sms` varchar(50) DEFAULT NULL,
  `id_ps` varchar(50) DEFAULT NULL,
  `no_skpi` varchar(50) DEFAULT NULL,
  `ktp` varchar(25) DEFAULT NULL,
  `nisn` varchar(30) DEFAULT NULL,
  `npwp` varchar(30) DEFAULT NULL,
  `jenis_tinggal` int DEFAULT NULL,
  `alat_transportasi` int DEFAULT NULL,
  `jenis_pendaftaran` int DEFAULT NULL,
  `jalur_pendaftaran` int DEFAULT NULL,
  `pembiayaan` int DEFAULT NULL,
  `validhp` int NOT NULL DEFAULT '0',
  `kodehp` varchar(5) NOT NULL,
  `vwisuda` int DEFAULT '0',
  `fktp` varchar(255) DEFAULT NULL,
  `fijasah` varchar(255) DEFAULT NULL,
  `fkk` varchar(255) DEFAULT NULL,
  `koreksi` int DEFAULT '0',
  `urutwisuda` int DEFAULT NULL,
  `vbar` int DEFAULT '0',
  `vfak` int DEFAULT '0',
  `vkeu` int DEFAULT '0',
  `vperpus` int DEFAULT '0',
  `vambil` int DEFAULT '0',
  `tglambil` datetime DEFAULT NULL,
  `fbayar` varchar(255) DEFAULT NULL,
  `a_terima_kps` char(1) DEFAULT '0',
  `no_kps` varchar(80) DEFAULT NULL,
  `nik_ayah` varchar(50) DEFAULT NULL,
  `tgl_lahir_ayah` date DEFAULT NULL,
  `id_penghasilan_ayah` smallint DEFAULT NULL,
  `nik_ibu` varchar(50) DEFAULT NULL,
  `tgl_lahir_ibu` date DEFAULT NULL,
  `id_penghasilan_ibu` smallint DEFAULT NULL,
  `nm_wali` varchar(50) DEFAULT NULL,
  `tgl_lahir_wali` date DEFAULT NULL,
  `id_jenjang_pendidikan_wali` smallint DEFAULT NULL,
  `id_pekerjaan_wali` smallint DEFAULT NULL,
  `id_penghasilan_wali` smallint DEFAULT NULL,
  `biaya_masuk_kuliah` double DEFAULT NULL,
  `is_data_validated` char(1) DEFAULT NULL,
  `is_buka_blokir_paket` char(1) DEFAULT NULL,
  `file_surat_pernyataan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Master Data Mahasiswa';

--
-- Dumping data for table `ms_mhs`
--

INSERT INTO `ms_mhs` (`nim`, `nama`, `gelardepan`, `gelarbelakang`, `kodeunit`, `periodedaftar`, `program`, `sistemkuliah`, `kodeangkatan`, `jalurpenerimaan`, `gelombang`, `sex`, `tmplahir`, `tgllahir`, `kodeagama`, `alamat`, `dusun`, `rt`, `rw`, `kelurahan`, `kecamatan`, `kode_will`, `kodekota`, `kodeprop`, `kodepos`, `telp`, `telp2`, `hp`, `hp2`, `email`, `email2`, `norekening`, `kodependidikan`, `goldarah`, `warganegara`, `statuskerja`, `pekerjaan`, `jabatan`, `alamatpersh`, `jenisinstansi`, `namaperusahaan`, `telppersh`, `penanggungdana`, `tglregistrasi`, `nip`, `statusmhs`, `bidangstudi`, `semmhs`, `ipk`, `skslulus`, `skslalu`, `ipslalu`, `batassks`, `cuti`, `sistemspp`, `periodewisuda`, `noijasah`, `tglijasah`, `notranskrip`, `tglselesai`, `mhstransfer`, `ptstatusdaftar`, `ptnim`, `ptthnmasuk`, `ptasal`, `ptjurusan`, `ptipk`, `ptthnlulus`, `sksasal`, `ptsemester`, `asalsmu`, `kotasmu`, `alamatsmu`, `telpsmu`, `propinsismu`, `jenissmu`, `statussmu`, `jurusansmu`, `unasmp`, `unasnilai`, `sttbmp`, `sttbnilai`, `sttbtahun`, `alasankeluar`, `statuspilih`, `statusnikah`, `namaistri`, `namaayah`, `statusayah`, `namaibu`, `statusibu`, `pendidikanayah`, `pendapatanortu`, `pekerjaanayah`, `pendidikanibu`, `pendapatanibu`, `pekerjaanibu`, `alamatortu`, `kotaortu`, `kodeposortu`, `telportu`, `catatankhusus`, `nimasal`, `cekalfakultas`, `cekalkeuangan`, `fileijazah`, `t_userid`, `t_updatetime`, `t_ipaddress`, `eps_kodeprop`, `eps_kodekab`, `eps_kodeptasal`, `eps_kodeprodiasal`, `eps_noijazahasal`, `eps_kodejenjangasal`, `eps_sksdiakui`, `nopendaftaran`, `groupkeuangan`, `sksasalbar`, `sisamasastudi`, `makulsisa`, `id_pd`, `id_reg_pd`, `id_sms`, `id_ps`, `no_skpi`, `ktp`, `nisn`, `npwp`, `jenis_tinggal`, `alat_transportasi`, `jenis_pendaftaran`, `jalur_pendaftaran`, `pembiayaan`, `validhp`, `kodehp`, `vwisuda`, `fktp`, `fijasah`, `fkk`, `koreksi`, `urutwisuda`, `vbar`, `vfak`, `vkeu`, `vperpus`, `vambil`, `tglambil`, `fbayar`, `a_terima_kps`, `no_kps`, `nik_ayah`, `tgl_lahir_ayah`, `id_penghasilan_ayah`, `nik_ibu`, `tgl_lahir_ibu`, `id_penghasilan_ibu`, `nm_wali`, `tgl_lahir_wali`, `id_jenjang_pendidikan_wali`, `id_pekerjaan_wali`, `id_penghasilan_wali`, `biaya_masuk_kuliah`, `is_data_validated`, `is_buka_blokir_paket`, `file_surat_pernyataan`) VALUES
('01118067', 'ABDULRAHMAN JAMAL ABDULRAHMAN BA ABBAD', NULL, NULL, '1011', '20181', '0', 'A', '18', '1', '20183', 'L', 'JEDDAH', '2000-06-25', '1', 'AL FAYAHAA DISTRIC JEDDAH ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '22246', NULL, NULL, '087753225809', '966508258060', NULL, NULL, NULL, NULL, 'AB', 'WNA', '0', NULL, NULL, NULL, NULL, NULL, NULL, 'Sendiri', '2018-08-25', NULL, 'A', NULL, '1', '0.00', NULL, '0', '0.00', '18', '0', '0', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ALIQBAL INJIL', '30', NULL, NULL, '94', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', NULL, 'JAMAL ABDULRAHMAN', '0', 'ASHWAQ SAEED', '0', '6', NULL, '10', '5', NULL, '10', 'AL FAYAHAA DISTRIC JEDDAH ', 'JEDDAH', NULL, '966508258060', 'BEASISWA YAMAN', '', '0', '0', NULL, '201502338', '2018-10-24 07:49:38', '192.168.2.32', '94', '30', NULL, NULL, NULL, NULL, '0', '018/011/VIII/2018', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('01118068', 'MAHMOOD MAAROF ABDULLAH ALWAN', NULL, NULL, '1011', '20181', '0', 'A', '18', '1', '20183', 'L', 'JEDDAH ', '1996-06-12', '1', 'JEDDAH ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '966500801597', '966500801597', '087753225809', NULL, '-', '-', NULL, NULL, NULL, 'WNA', '0', NULL, NULL, NULL, NULL, NULL, NULL, 'Sendiri', '2018-08-25', NULL, 'A', NULL, '1', '0.00', NULL, '0', '0.00', '18', '0', '0', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, '2017', NULL, NULL, 'ALDERIAH SECONDARY SCHOOL ', '30', NULL, NULL, '94', NULL, '0', 'IPA ', NULL, NULL, NULL, NULL, '2017', NULL, '0', '0', NULL, 'MAAROF ALI ', '0', 'SWAFAAH', '0', '5', NULL, '10', '5', NULL, '10', 'JEDDAH ', NULL, NULL, '966500801597', 'BEASISWA YAMAN ', '', '0', '0', NULL, '201502338', '2018-10-24 07:49:51', '192.168.2.32', '94', '30', NULL, NULL, NULL, NULL, '0', '021/011/VIII/2018', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('01118072', 'VINCENTIUS FERNANDO LEWA LODA', NULL, NULL, '1011', '20181', '0', 'A', '18', '2', 'RS', 'L', 'WAIKABUBAK', '1995-01-21', '2', 'JL MENUR PLUMPUNGAN GG 2A NO 4', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '081358950201', '081358950201', 'lewaloda@gmail.com', 'LEWALODA@GMAIL.COM', NULL, NULL, 'AB', 'WNI', '0', NULL, NULL, NULL, NULL, NULL, NULL, 'Sendiri', '2018-08-31', '01011601', 'A', NULL, '1', '2.93', '51', '0', '0.00', '24', '0', '0', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, '2012', NULL, NULL, 'ENDE', '11', 'JL MELATI', NULL, '24', NULL, '0', 'IPS', NULL, NULL, NULL, NULL, '2012', NULL, '0', '0', NULL, 'KANISIUS S PAE', '0', 'ARNOLDA B BELLA', '0', '5', NULL, '1', '5', NULL, '11', 'JL MENUR PLUMPUNGAN GG 2A NO 4', 'FLOREST ', NULL, '085336499347', 'UANG MUKA PEMBAYARAN RP . 1.350.000\r\nPROMO JUMAT SODAQOH FREE UP', '1210107975', '0', '0', NULL, '201502338', '2018-09-15 13:42:03', '192.168.2.32', '24', '11', NULL, NULL, NULL, NULL, '0', '028/011/VIII/2018', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '22776', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('01118080', 'DIVANIA WAIN PINTO BRAZ', NULL, NULL, '1011', '20181', '0', 'A', '18', '1', '20183', 'P', 'DILI', '1999-10-31', '3', 'JL. MOJOPAHIT NO. 12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '081310451115', '081310451115', '081310451115', NULL, 'divaniapinto99@gmail.com', 'DIVANIAPINTO99@GMAIL.COM', NULL, NULL, NULL, 'WNI', '0', NULL, NULL, NULL, NULL, NULL, NULL, 'Sendiri', '2018-09-05', '01011601', 'A', NULL, '1', '3.50', '2', '0', '0.00', '18', '0', '0', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, '2017', NULL, NULL, 'SAOJOSE PERARIO', '33', NULL, NULL, '94', NULL, NULL, 'IPA', NULL, NULL, NULL, NULL, '2017', NULL, '0', '0', NULL, 'MATIAS DASILVA BRAZ', '0', 'CLAUDINA PINTO', '0', '7', NULL, '4', '7', NULL, '10', 'TIMOR LESTE', 'DILI', NULL, '77349710', 'GELOMBANG III , UP DISC 25 ', '', '0', '0', NULL, '201502338', '2018-09-05 16:13:22', '192.168.2.32', '94', '33', NULL, NULL, 'DL 09072 ', NULL, '0', '003/011/IX/2018', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('01119001', 'MELITANIA SANTI', NULL, NULL, '1011', '20191', '0', 'A', '19', '1', '20193', 'P', 'JOMBANG', '1997-06-05', '1', 'JL GUNAWAN NO.29, GUWO LATSARI, MOJOWARNO, JOMBANG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '61475', '081236665670', '081236665670', '081236665670', NULL, NULL, NULL, NULL, NULL, NULL, 'WNI', '1', 'CV ANUGRAH M', NULL, NULL, 'SWASTA', NULL, NULL, 'Sendiri', '2018-11-30', NULL, 'A', NULL, '1', '2.00', '2', '0', '0.00', '18', '0', '0', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, '2015', NULL, NULL, 'MA DARUL ULUM', '15', NULL, NULL, '05', NULL, '0', 'IPA ', NULL, NULL, NULL, NULL, '2015', NULL, '0', '0', NULL, 'YOYOK', '0', 'SRI AMIK ', '0', '4', NULL, NULL, '4', NULL, NULL, 'GEDANGAN SIDOARJO', NULL, '61254', NULL, 'FREE UP (100 PENDAFTAR PERTAMA)', NULL, '0', '0', NULL, '201502338', '2019-09-06 15:07:47', '192.168.2.32', '05', '15', NULL, NULL, 'MA 160004546', NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '3517074506970001', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('01119027', 'ARIF SUDIBYO', NULL, NULL, '1011', '20191', '0', 'B', '19', '1', '20195', 'L', 'TULUNGAGUNG', '1992-05-16', '2', 'JL MAYOR SUJADI 136 RT/RW 04/01 JEPUN TULUNAGGUNG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '66218', NULL, NULL, '087755145144', '087755145144', 'ARIFSUDIBYO@GMAIL.COM', 'ARIFSUDIBYO@GMAIL.COM', NULL, NULL, 'O', 'WNI', '1', 'SWASTA', 'STAFF', NULL, 'SWASTA', NULL, NULL, 'Sendiri', '2019-05-28', NULL, 'A', NULL, '1', '0.00', NULL, NULL, '0.00', '18', '0', '0', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, '0.00', '2010', NULL, NULL, 'SANTO THOMAS AQUINO', '04', 'TULUNGAGGUNG', NULL, '05', NULL, '0', 'IPA ', NULL, NULL, NULL, NULL, '2010', NULL, '0', '0', NULL, 'BUDI HARTONO TIO', '0', 'TJENG MIE YOU AL YUSANA', '0', '5', NULL, '5', '5', NULL, '11', 'JL MAYOR SUJADI 136 RT/RW 04/01 JEPUN TULUNAGGUNG', 'TULUNGAGUNG', '66218', '087755145144', 'DIS 30UP', NULL, '0', '0', NULL, '201502338', '2019-08-29 10:08:04', '192.168.2.32', '05', '04', NULL, NULL, 'DN-05 MA 0047172', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '3504011605920001', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('01120003', 'LIPINUS PANDI WAHANG', NULL, NULL, '1011', '20201', '0', 'B', '20', '1', '20193', 'L', 'WUKURAMBA', '1997-11-12', '2', 'WUKU RAMBA KEL. LAMBANAPU, KEC. KAMBERA', NULL, '009', '003', 'LAMBANAPU', 'KAMBERA', NULL, '02', '24', NULL, NULL, NULL, '085791549331', NULL, 'umbuwahang4@gmail.com', NULL, NULL, NULL, 'A', 'WNI', '1', 'STAFF', NULL, 'KLAMPIS', 'SWASTA', 'TOKO 42 ', NULL, 'Sendiri', '2020-01-20', '01010003', 'A', NULL, '6', '3.41', '104', '15', '3.30', '24', '0', '0', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, '2016', NULL, NULL, 'SMAN 1 WAINGAPU', '02', NULL, NULL, '24', NULL, '0', 'IPA ', NULL, NULL, NULL, NULL, '2016', NULL, '0', '0', NULL, 'NDAPA NENI', '0', 'KONGA KAKU', '0', '4', NULL, '6', '3', NULL, '6', 'WUKURAMBA', NULL, NULL, '081359915434', '100 FREE UP', NULL, '0', '0', NULL, '01120003', '2025-04-28 15:01:34', '114.5.243.199', '24', '02', NULL, NULL, 'DN-24 Ma/06 0011278', NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '5311161012930004', '-', '-', 3, 13, 1, 6, 1, 2, '44994', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('01120005', 'ANISA JUMARNIS', NULL, NULL, '1011', '20201', '0', 'B', '20', '1', '20193', 'P', 'SURABAYA ', '2000-11-02', '1', 'JL. KALIJUDAN 15/36 SURABAYA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '089675644989', NULL, 'nisaanisaj@gmail.com', NULL, NULL, NULL, NULL, 'WNI', '1', 'STAFF', 'STAFF', 'ATOM MALL LT 1 SURABAYA', 'SWASTA', 'APOTEK SINAR B ', NULL, 'Sendiri', '2020-01-27', '01011501', 'A', NULL, '1', '0.00', NULL, NULL, '0.00', '18', '0', '0', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, '2019', NULL, NULL, 'SMK NEGERI 10 SURABAYA', '58', 'JL.KEPUTIH TEGAL SURABAYA', NULL, '05', NULL, '0', 'AKUNTANSI', NULL, NULL, NULL, NULL, '2019', NULL, '0', '0', NULL, 'MOH JUNAIDI ', '0', 'SUMARNI', '0', '3', NULL, NULL, '4', NULL, NULL, 'JL. KALIJUDAN 15/36 ', 'SURABAYA ', NULL, '0895335574055', '100 PENDAFTAR PERTAMA ', NULL, '0', '0', NULL, '1911245', '2020-07-24 10:27:16', '192.168.2.32', '05', '58', NULL, NULL, 'M-SMK/13-3/0439132', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '3578264211000001', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('01120035', 'MUSARROPAH', NULL, NULL, '1011', '20201', '0', 'A', '20', '1', '20206', 'P', 'SAMPANG', '2003-07-20', '1', 'DSN NGABARAN ASEM RAJA JENGIK SAMPANG', 'NGABARAN', '00', '00', 'ASEM RAJA ', 'Kec. Jrengik', NULL, '27', '05', '69272', '0877813571', NULL, '0877813571', NULL, 'musarmokonk@gmail.com', NULL, NULL, NULL, 'AB', 'WNI', '0', NULL, NULL, NULL, NULL, NULL, NULL, 'Sendiri', '2020-09-01', '01011301', 'A', NULL, '9', '3.19', '131', '6', '3.00', '24', '1', '0', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, '2020', NULL, NULL, 'SMAN 1 TORJUN', '27', 'SAMPANG', NULL, '05', NULL, '0', 'IPS', NULL, NULL, NULL, NULL, '2020', NULL, '0', '0', NULL, 'SADENON', '0', 'RENYEM', '0', '2', NULL, '10', '2', NULL, '10', 'DSN NGABARAN ASEM RAJA JENGIK SAMPANG', 'SAMPANG', NULL, '0877580214553', 'PROMO POT UP 50', NULL, '0', '0', NULL, '01120035', '2024-12-07 07:24:05', '140.213.57.219', '05', '27', NULL, NULL, 'DN-05/M SMA/13 0242109', NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '3527076007030001', '0', '0', 1, 99, 1, 6, 1, 2, '07879', 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('01121003', 'VARIAN DWI TURISTIANTO', NULL, NULL, '1011', '20211', '0', 'A', '21', '1', '20211', 'L', 'SURABAYA ', '1999-03-19', '1', 'JL PUCANG SEWU 1/10 SURABAYA', NULL, '008', '009', 'PUCANG SEWU', 'Kec. Gubeng', '56010', '58', '05', '60283', NULL, NULL, '081315158228', NULL, 'variandwi99@gmail.com', NULL, NULL, NULL, 'AB', 'WNI', '1', 'BARISTA', 'STAFF', NULL, 'SWASTA', 'CITA RASA ATJEH', NULL, 'Sendiri', '2021-03-09', '01011501', 'A', NULL, '1', '2.97', '19', '0', '0.00', '18', '0', '0', NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, '2017', NULL, NULL, 'SMK PGRI 4 SURABAYA', '58', 'SURABAYA', NULL, '05', NULL, '0', 'AUDIO', NULL, NULL, NULL, NULL, '2017', NULL, '0', '0', NULL, 'BOEDY SOEDJANJO', '0', 'NINA POEDJI', '0', '5', NULL, '10', '5', NULL, '10', 'JL PUCANG SEWU 1/10 SURABAYA', 'SURABAYA', '60283', '083849282740', 'PROMO FREE UP 200 PENDAFTAR', NULL, '0', '0', NULL, '1110141 ', '2021-12-14 10:52:06', '103.78.83.130', '05', '58', NULL, NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '3578081903990001', '0', '0', 1, 13, 1, 6, 1, 2, '', 0, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ms_periode`
--

CREATE TABLE `ms_periode` (
  `periode` varchar(6) NOT NULL,
  `uraian` varchar(100) DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `aktif_pemilih` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Master Data Periode Perkuliahan Semester atau Sesi\nMis';

--
-- Dumping data for table `ms_periode`
--

INSERT INTO `ms_periode` (`periode`, `uraian`, `status`, `aktif_pemilih`) VALUES
('19901', '', 0, '0'),
('19951', NULL, 0, '0'),
('19961', NULL, 0, '0'),
('19971', NULL, 0, '0'),
('19972', '', 0, '0'),
('19981', NULL, 0, '0'),
('19982', '', 0, '0'),
('19991', NULL, 0, '0'),
('19992', '', 0, '0'),
('20001', '', 0, '0'),
('20002', '', 0, '0'),
('20011', '', 0, '0'),
('20012', '', 0, '0'),
('20021', '', 0, '0'),
('20022', '', 0, '0'),
('20031', '', 0, '0'),
('20032', '', 0, '0'),
('20041', '', 0, '0'),
('20042', '', 0, '0'),
('20051', '', 0, '0'),
('20052', '', 0, '0'),
('20061', '', 0, '0'),
('20062', '', 0, '0'),
('20071', '', 0, '0'),
('20072', '', 0, '0'),
('20081', '', 0, '0'),
('20082', '', 0, '0'),
('20091', '', 0, '0'),
('20092', '', 0, '0'),
('20093', '', 0, '0'),
('20101', '', 0, '0'),
('20102', '', 0, '0'),
('20103', '', 0, '0'),
('20111', '', 0, '0'),
('20112', '', 0, '0'),
('20113', '', 0, '0'),
('20121', '', 0, '0'),
('20122', '', 0, '0'),
('20131', '', 0, '0'),
('20132', '', 0, '0'),
('20141', '', 0, '0'),
('20142', '', 0, '0'),
('20151', '', 0, '0'),
('20152', '', 0, '0'),
('20161', '', 0, '0'),
('20162', '', 0, '0'),
('20171', '', 0, '0'),
('20172', '', 0, '0'),
('20181', '', 0, '0'),
('20182', '', 0, '0'),
('20183', NULL, 0, '0'),
('20191', '', 0, '0'),
('20192', '', 0, '0'),
('20193', NULL, 0, '0'),
('20201', '', 0, '0'),
('20202', '', 0, '0'),
('20203', NULL, 0, '0'),
('20211', NULL, 0, '0'),
('20212', '', 0, '0'),
('20213', NULL, 0, '0'),
('20221', NULL, 1, '0'),
('20222', '', 0, '0'),
('20223', NULL, 0, '0'),
('20231', NULL, 0, '0'),
('20232', NULL, 0, '0'),
('20233', NULL, 0, '0'),
('20241', NULL, 0, '0'),
('20242', NULL, 0, '0'),
('20251', NULL, 0, '1'),
('20252', NULL, 0, '0'),
('20261', NULL, 0, '0');

-- --------------------------------------------------------

--
-- Table structure for table `ms_unit`
--

CREATE TABLE `ms_unit` (
  `kode_ps` char(5) DEFAULT NULL,
  `id_sms` varchar(50) DEFAULT NULL,
  `id_ps` varchar(50) DEFAULT NULL,
  `kodeunit` varchar(6) NOT NULL,
  `parentunit` varchar(6) DEFAULT NULL,
  `namaunit` varchar(60) DEFAULT NULL,
  `namasingkat` varchar(20) DEFAULT NULL,
  `level` decimal(1,0) DEFAULT NULL,
  `gelar` varchar(50) DEFAULT NULL,
  `ketua` varchar(10) DEFAULT NULL,
  `sekretaris` varchar(10) DEFAULT NULL,
  `pembantu1` varchar(10) DEFAULT NULL,
  `pembantu2` varchar(10) DEFAULT NULL,
  `pembantu3` varchar(10) DEFAULT NULL,
  `kodeurutan` int DEFAULT NULL,
  `program` varchar(5) DEFAULT NULL,
  `tahapfrs` varchar(1) DEFAULT '0',
  `akreditasi` varchar(15) DEFAULT NULL,
  `noskdikti` varchar(40) DEFAULT NULL,
  `tglskdikti` date DEFAULT NULL,
  `tglakhirsk` date DEFAULT NULL,
  `statusunit` varchar(1) DEFAULT NULL,
  `tglberdiri` date DEFAULT NULL,
  `noskbanpt` varchar(75) DEFAULT NULL,
  `tglskakreditasi` date DEFAULT NULL,
  `epskodeprodi` varchar(10) DEFAULT NULL,
  `epskodefak` varchar(10) DEFAULT NULL,
  `tglskbanpt` date DEFAULT NULL,
  `tglakhirbanpt` date DEFAULT NULL,
  `tgldihapus` date DEFAULT NULL,
  `namaoperator` varchar(50) DEFAULT NULL,
  `telpoperator` varchar(15) DEFAULT NULL,
  `faxprodi` varchar(15) DEFAULT NULL,
  `emailprodi` varchar(50) DEFAULT NULL,
  `telpprodi` varchar(15) DEFAULT NULL,
  `kodefreq` varchar(2) DEFAULT NULL,
  `kodepelfreq` varchar(2) DEFAULT NULL,
  `isakademik` char(1) DEFAULT NULL,
  `t_userid` varchar(30) DEFAULT NULL,
  `t_updatetime` varchar(30) DEFAULT NULL,
  `t_ipaddress` varchar(30) DEFAULT NULL,
  `nama_depan` varchar(30) DEFAULT NULL,
  `nameunit` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Unit Kerja atau Bidang Studi';

--
-- Dumping data for table `ms_unit`
--

INSERT INTO `ms_unit` (`kode_ps`, `id_sms`, `id_ps`, `kodeunit`, `parentunit`, `namaunit`, `namasingkat`, `level`, `gelar`, `ketua`, `sekretaris`, `pembantu1`, `pembantu2`, `pembantu3`, `kodeurutan`, `program`, `tahapfrs`, `akreditasi`, `noskdikti`, `tglskdikti`, `tglakhirsk`, `statusunit`, `tglberdiri`, `noskbanpt`, `tglskakreditasi`, `epskodeprodi`, `epskodefak`, `tglskbanpt`, `tglakhirbanpt`, `tgldihapus`, `namaoperator`, `telpoperator`, `faxprodi`, `emailprodi`, `telpprodi`, `kodefreq`, `kodepelfreq`, `isakademik`, `t_userid`, `t_updatetime`, `t_ipaddress`, `nama_depan`, `nameunit`) VALUES
('71008', NULL, NULL, '0', NULL, 'Universitas Narotama', 'UNNAR', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, '1081/SK/BAN-PT/Ak-PPJ/PT/XII/2021\r\n', '2021-12-22', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL),
(NULL, NULL, NULL, '1010', '0', 'Fakultas Ekonomi dan Bisnis', 'FE', '1', NULL, '01020811', NULL, '01011214', NULL, NULL, 100, NULL, 'A', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '01010606', '2018-07-11 08:12:07', '192.168.196.124', NULL, 'Faculty Economic and Business'),
('62201', '06e2ed67-7efc-43cd-9a6b-f5a7cba47730', 'bad80801-9cc0-45d5-8fb6-af4e75966f0a', '1011', '1010', 'Akuntansi', 'EA', '2', 'SARJANA AKUNTANSI (S.Ak.)', '01012201', '01021120', NULL, NULL, NULL, 101, '0', '0', 'BAIK', '0395/O/1986', '1986-05-23', NULL, NULL, NULL, '2226/DE/A.5/AR.10/IV/2025', '2025-05-24', '62201', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '1112148', '2015-05-27 14:52:03', '10.10.1.125', NULL, NULL),
('61201', '0adc17cf-38fa-48b9-b761-ed74724783a2', 'bad80801-9cc0-45d5-8fb6-af4e75966f0a', '1012', '1010', 'Manajemen', 'EM', '2', 'SARJANA MANAJEMEN (S.M.)', '01029204', NULL, NULL, NULL, NULL, 102, '0', '0', 'B', '0395/O/1986', '1986-05-23', NULL, NULL, NULL, '14019/SK/BAN-PT/Ak-PPJ/S/XII/2021\r\n', '2021-12-29', '61201', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '0808088', '2018-09-01 09:52:25', '192.168.1.2', NULL, NULL),
(NULL, NULL, NULL, '1020', '0', 'Fakultas Hukum', 'FH', '1', NULL, '02031121', NULL, '02031223', NULL, NULL, 200, NULL, 'A', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '01010606', '2018-07-11 08:12:27', '192.168.196.124', NULL, NULL),
('74201', 'a5fbc55a-9a1d-4ce7-819e-25a112bd5519', 'bad80801-9cc0-45d5-8fb6-af4e75966f0a', '1021', '1020', 'Ilmu Hukum', 'IH', '2', 'SARJANA HUKUM (S.H.)', '02031601', NULL, NULL, NULL, NULL, 201, '0', '0', 'B', '0395/O/1986', '1986-05-23', NULL, NULL, NULL, '13039/SK/BAN-PT/Ak-PPJ/S/XII/2021', '2021-12-14', '74201', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '01010606', '2016-10-20 13:36:28', '192.168.1.26', NULL, NULL),
(NULL, NULL, NULL, '1030', '0', 'Fakultas Teknik', 'FTS', '1', NULL, '03040109', '03041220', '03041220', NULL, NULL, 300, NULL, 'A', 'A', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '01010606', '2015-10-07 09:49:43', '192.168.1.220', NULL, NULL),
('22201', '6ff40e56-bfde-4655-8f34-5c064fff0581', 'bad80801-9cc0-45d5-8fb6-af4e75966f0a', '1031', '1030', 'Teknik Sipil', 'TS', '2', 'SARJANA TEKNIK (S.T.)', '03040710', '03041220', NULL, NULL, NULL, 301, '0', '0', 'B', '0395/O/1986', '1986-05-23', NULL, NULL, NULL, '13709/SK/BAN-PT/Ak-PPJ/S/XII/2021\r\n', '2021-12-28', '22201', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '01010606', '2018-07-11 08:13:30', '192.168.196.124', NULL, NULL),
(NULL, NULL, NULL, '1040', '0', 'Fakultas Ilmu Komputer', 'FIK', '1', NULL, '04060906', NULL, '04050806', NULL, NULL, 400, NULL, 'A', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '0808088', '2018-09-07 10:42:39', '192.168.1.2', NULL, NULL),
('56201', '61ce5fa7-c411-4850-835d-a816458f831c', 'bad80801-9cc0-45d5-8fb6-af4e75966f0a', '1041', '1040', 'Sistem Komputer', 'SK', '2', 'SARJANA KOMPUTER (S.Kom.)', '0404060', NULL, NULL, NULL, NULL, 401, '0', '0', 'BAIK SEKALI', '3104/D/T/2001', '2001-09-26', NULL, NULL, NULL, '035/SK/LAM-INFOKOM/Ak.B/S/VIII/2025', '2025-08-08', '56201', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '01010606', '2015-06-22 08:18:35', '172.16.1.9', NULL, NULL),
('57201', 'b67e146e-2151-4897-baf5-d6f342425a3f', 'bad80801-9cc0-45d5-8fb6-af4e75966f0a', '1042', '1040', 'Sistem Informasi', 'SI', '2', 'SARJANA KOMPUTER (S.Kom.)', '01010606', NULL, NULL, NULL, NULL, 402, '0', '0', 'BAIK SEKALI', '3104/D/T/2001', '2001-09-26', NULL, NULL, NULL, '6511/SK/BAN-PT/Ak.Ppj/S/IX/2022', '2022-09-27', '57201', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '01010606', '2015-06-22 08:17:40', '172.16.1.9', NULL, NULL),
('55202', '623fd4b6-22ca-4e42-b5e5-80e979682a7c', 'bad80801-9cc0-45d5-8fb6-af4e75966f0a', '1043', '1040', 'Teknik Informatika', 'TI', '2', 'SARJANA KOMPUTER (S.Kom.)', '04060302', '04061401', NULL, NULL, NULL, 403, '0', '0', 'BAIK', '52/E/O/2014', '2014-04-29', NULL, NULL, NULL, '1999/SK/BAN-PT/AK-ISK/S/IV/2021', '2021-04-13', '55201', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '1112148', '2015-05-27 14:49:35', '10.10.1.125', NULL, NULL),
(NULL, NULL, NULL, '1050', '0', 'Fakultas Keguruan dan Ilmu Pendidikan', 'FKIP', '1', 'SARJANA PENDIDIKAN (S.Pd.)', '04051210', NULL, NULL, NULL, NULL, 500, '0', 'A', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '01010606', '2015-10-22 10:41:51', '192.168.1.220', NULL, 'Faculty Teachery and Education'),
('86207', NULL, NULL, '1051', '1050', 'Pendidikan Guru Pendidikan Anak Usia Dini', 'PGPUD', '2', 'SARJANA PENDIDIKAN (S.Pd.)', '02031120', NULL, NULL, NULL, NULL, 501, '0', '0', 'B', '475/E/O/2014', '2014-10-08', NULL, NULL, NULL, '5948/SK/BAN-PT/Akred/S/IX/2020', '2020-09-23', '86207', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '01010606', '2017-10-17 09:59:58', '10.10.1.20', NULL, NULL),
('61101', 'c70be59b-9814-49f3-9d94-18ffe4749666', 'bad80801-9cc0-45d5-8fb6-af4e75966f0a', '1111', '1010', 'Magister Manajemen', 'MM', '2', 'MAGISTER MANAJEMEN (M.M.)', '01020709', '01011214', NULL, NULL, NULL, 103, '1', '0', 'BAIK SEKALI', '212/DIKTI/Kep/2000', '2000-06-27', NULL, NULL, NULL, '12750/SK/BAN-PT/Akred-PMT/M/XII/2021', '2021-12-01', '61101', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '01010606', '2018-07-11 08:12:44', '192.168.196.124', 'Magister', NULL),
('74101', '3e001137-69ea-476c-8781-ab928fb7b5b7', 'bad80801-9cc0-45d5-8fb6-af4e75966f0a', '1121', '1020', 'Magister Ilmu Hukum', 'MH', '2', 'MAGISTER HUKUM (M.H.)', '0907103', NULL, NULL, NULL, NULL, 202, '1', '0', 'B', '237/DIKTI/Kep/2000', '2000-07-19', NULL, NULL, NULL, '6827/SK/BAN-PT/Ak.Ppj/M/VI/2025', '2025-06-14', '74101', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '01010606', '2018-07-11 08:13:14', '192.168.196.124', 'Magister', NULL),
('74102', '9130b67c-7ed8-4622-9069-94208d48bae6', 'bad80801-9cc0-45d5-8fb6-af4e75966f0a', '1122', '1020', 'Magister Kenotariatan', 'MKn', '2', 'MAGISTER KENOTARIATAN (M.Kn.)', '05080808', '02031121', NULL, NULL, NULL, 203, '1', '0', 'B', '236/E/O/2011', '2011-10-13', NULL, NULL, NULL, '13069/SK/BAN-PT/Ak-PPJ/M/XII/2021\r\n', '2021-12-14', '74102', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '01010606', '2014-04-02 08:33:30', '11.11.11.254', 'Magister', NULL),
('22101', NULL, NULL, '1131', '1030', 'Magister Teknik Sipil', 'MT', '2', 'MAGISTER TEKNIK (M.T.)', '03049203', NULL, NULL, NULL, NULL, 302, '1', '0', 'BAIK SEKALI', '371/KPT/I/2017\r\n', '2017-06-19', NULL, NULL, NULL, '0126/SK/LAM Teknik/AM/IV/2024', '2024-04-21', '22101', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', '01010606', '2017-11-22 12:17:51', '10.10.1.20', NULL, NULL),
(NULL, NULL, NULL, '9999', NULL, 'Mitra Industri dan Perguruan Tinggi', 'MBKM', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'A', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sc_role`
--

CREATE TABLE `sc_role` (
  `idrole` varchar(10) NOT NULL,
  `namarole` varchar(30) DEFAULT NULL,
  `isactive` decimal(1,0) DEFAULT '0',
  `roledefpage` varchar(100) DEFAULT NULL,
  `t_userid` varchar(10) DEFAULT NULL,
  `t_updatetime` varchar(30) DEFAULT NULL,
  `t_ipaddress` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Group User';

--
-- Dumping data for table `sc_role`
--

INSERT INTO `sc_role` (`idrole`, `namarole`, `isactive`, `roledefpage`, `t_userid`, `t_updatetime`, `t_ipaddress`) VALUES
('admin', 'Administrator', '1', '../akademik/home.php', NULL, NULL, NULL),
('kemhsan', 'Kemahasiswaan', '0', NULL, NULL, NULL, NULL),
('mhs', 'Mahasiswa', '0', '../akademik/home_mhs.php', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sc_user`
--

CREATE TABLE `sc_user` (
  `userid` varchar(10) NOT NULL,
  `username` varchar(60) DEFAULT NULL,
  `statususer` decimal(1,0) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `hints` varchar(255) DEFAULT NULL,
  `isactive` decimal(1,0) NOT NULL DEFAULT '0',
  `expirationdate` date DEFAULT NULL,
  `lastlogintime` varchar(30) DEFAULT NULL,
  `lastloginip` varchar(30) DEFAULT NULL,
  `validationcode` varchar(30) DEFAULT NULL,
  `t_userid` varchar(10) DEFAULT NULL,
  `t_updatetime` varchar(30) DEFAULT NULL,
  `t_ipaddress` varchar(30) DEFAULT NULL,
  `kode_rfid` varchar(15) DEFAULT NULL,
  `validasi_aipt` char(1) DEFAULT '0',
  `petugas_mod` char(1) DEFAULT NULL,
  `baca` char(1) DEFAULT NULL,
  `tulis_kebijakan` char(1) DEFAULT NULL,
  `tulis_fasilitas` char(1) DEFAULT NULL,
  `tulis_pelayanan` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='User';

--
-- Dumping data for table `sc_user`
--

INSERT INTO `sc_user` (`userid`, `username`, `statususer`, `password`, `email`, `hints`, `isactive`, `expirationdate`, `lastlogintime`, `lastloginip`, `validationcode`, `t_userid`, `t_updatetime`, `t_ipaddress`, `kode_rfid`, `validasi_aipt`, `petugas_mod`, `baca`, `tulis_kebijakan`, `tulis_fasilitas`, `tulis_pelayanan`) VALUES
('01118068', 'MAHMOOD MAAROF ABDULLAH ALWAN', NULL, '01118068', NULL, '3346', '0', NULL, '2019-07-28 19:29:26', NULL, NULL, NULL, NULL, '103.213.130.151, 103.213.130.1', '0011596885', '0', NULL, NULL, NULL, NULL, NULL),
('01118072', 'VINCENTIUS FERNANDO LEWA LODA', NULL, '970af30e481057c48f87e101b61e6994', NULL, '1427', '0', NULL, '2018-09-10 11:14:07', NULL, NULL, NULL, NULL, '172.27.47.10', '0005131033', '0', NULL, NULL, NULL, NULL, NULL),
('01118080', 'DIVANIA WAIN PINTO BRAZ', NULL, '9969e5817467c11ff15639d0ed517c7a', NULL, '8455', '0', NULL, '2018-09-12 11:10:10', NULL, NULL, NULL, NULL, '158.140.171.38', '0005167336', '0', NULL, NULL, NULL, NULL, NULL),
('01119001', 'MELITANIA SANTI', NULL, '4572101f572c37f203cd8690fe6e4eeb', NULL, '9671', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0011595746', '0', NULL, NULL, NULL, NULL, NULL),
('01119027', 'ARIF SUDIBYO', NULL, '049671e28a386427e432b3370a22aae4', NULL, '5610', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0013743344', '0', NULL, NULL, NULL, NULL, NULL),
('01120003', 'LIPINUS PANDI WAHANG', NULL, 'ef62dbc64215b8f53b4d9f7b59350e70', NULL, '4831', '0', NULL, '2025-11-10 07:56:26', NULL, NULL, NULL, NULL, '114.8.224.186', '0004876448', '0', NULL, NULL, NULL, NULL, NULL),
('01120005', 'ANISA JUMARNIS', NULL, 'bd48f59a9f04aefd7708058b717453af', NULL, '7206', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0003193861', '0', NULL, NULL, NULL, NULL, NULL),
('01120035', 'MUSARROPAH', NULL, '172ef5a94b4dd0aa120c6878fc29f70c', NULL, '8088', '0', NULL, '2025-11-20 22:00:51', NULL, NULL, NULL, NULL, '182.8.122.81', '0008243677', '0', NULL, NULL, NULL, NULL, NULL),
('01121003', 'VARIAN DWI TURISTIANTO', NULL, '2156795824e042092b04e970977114cd', NULL, '7727', '0', NULL, '2021-10-27 07:50:21', NULL, NULL, NULL, NULL, '180.247.237.78', '0007648128', '0', NULL, NULL, NULL, NULL, NULL),
('04060910', 'Achmad Zakki Falani', NULL, '4d14ecda749c57ea9cdfa8b1e7b6a837', NULL, '1635', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sc_userrole`
--

CREATE TABLE `sc_userrole` (
  `idrole` varchar(10) NOT NULL,
  `userid` varchar(10) NOT NULL,
  `kodeunit` varchar(6) NOT NULL,
  `isdefault` decimal(1,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Group User';

--
-- Dumping data for table `sc_userrole`
--

INSERT INTO `sc_userrole` (`idrole`, `userid`, `kodeunit`, `isdefault`) VALUES
('admin', '04060910', '0', '1'),
('mhs', '01118068', '1011', '1'),
('mhs', '01118072', '1011', '1'),
('mhs', '01118080', '1011', '1'),
('mhs', '01119001', '1011', '1'),
('mhs', '01119027', '1011', '1'),
('mhs', '01120003', '1011', '1'),
('mhs', '01120005', '1011', '1'),
('mhs', '01120035', '1011', '1'),
('mhs', '01121003', '1011', '1');

-- --------------------------------------------------------

--
-- Table structure for table `z_evote_mst_hima`
--

CREATE TABLE `z_evote_mst_hima` (
  `id` int NOT NULL,
  `uuid` char(36) DEFAULT NULL,
  `kodeunit` varchar(6) DEFAULT NULL,
  `periode` varchar(9) DEFAULT NULL,
  `nim_ketua` varchar(10) DEFAULT NULL,
  `foto_ketua` varchar(255) DEFAULT NULL,
  `nim_wakil` varchar(10) DEFAULT NULL,
  `foto_wakil` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `z_evote_mst_presiden_bem`
--

CREATE TABLE `z_evote_mst_presiden_bem` (
  `id` int NOT NULL,
  `uuid` char(36) DEFAULT NULL,
  `periode` varchar(9) DEFAULT NULL,
  `nim_ketua` varchar(10) DEFAULT NULL,
  `foto_ketua` varchar(255) DEFAULT NULL,
  `nim_wakil` varchar(10) DEFAULT NULL,
  `foto_wakil` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `z_evote_mst_tahun_periode`
--

CREATE TABLE `z_evote_mst_tahun_periode` (
  `id` int NOT NULL,
  `periode` varchar(9) DEFAULT NULL,
  `is_aktif` char(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `z_evote_trs_vote`
--

CREATE TABLE `z_evote_trs_vote` (
  `id` int NOT NULL,
  `id_ms_presiden_bem` int DEFAULT NULL,
  `id_ms_hima` int DEFAULT NULL,
  `periode` varchar(9) DEFAULT NULL,
  `nim` varchar(10) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ms_mhs`
--
ALTER TABLE `ms_mhs`
  ADD PRIMARY KEY (`nim`),
  ADD KEY `dosenwali` (`nip`),
  ADD KEY `jalurmhs` (`jalurpenerimaan`),
  ADD KEY `klotermhs` (`kodeangkatan`),
  ADD KEY `agamamhs` (`kodeagama`),
  ADD KEY `pendidikanmhs` (`kodependidikan`),
  ADD KEY `statusmhs` (`statusmhs`),
  ADD KEY `programstudimhs` (`program`),
  ADD KEY `mhsprodi` (`kodeunit`),
  ADD KEY `pekerjaanayah` (`pekerjaanibu`),
  ADD KEY `pekerjaanibu` (`pekerjaanayah`),
  ADD KEY `pendidikanayah` (`pendidikanibu`),
  ADD KEY `pendidikanibu` (`pendidikanayah`),
  ADD KEY `periodekulmhs` (`periodedaftar`),
  ADD KEY `periodewisudamhs` (`periodewisuda`),
  ADD KEY `sist_kulmhs` (`sistemkuliah`),
  ADD KEY `pendapatanortu` (`pendapatanortu`);

--
-- Indexes for table `ms_periode`
--
ALTER TABLE `ms_periode`
  ADD PRIMARY KEY (`periode`);

--
-- Indexes for table `ms_unit`
--
ALTER TABLE `ms_unit`
  ADD PRIMARY KEY (`kodeunit`),
  ADD KEY `parentunit` (`parentunit`),
  ADD KEY `FK_ms_unit` (`program`);

--
-- Indexes for table `sc_role`
--
ALTER TABLE `sc_role`
  ADD PRIMARY KEY (`idrole`);

--
-- Indexes for table `sc_user`
--
ALTER TABLE `sc_user`
  ADD PRIMARY KEY (`userid`);

--
-- Indexes for table `sc_userrole`
--
ALTER TABLE `sc_userrole`
  ADD PRIMARY KEY (`idrole`,`userid`,`kodeunit`),
  ADD KEY `fk_unitrole` (`kodeunit`),
  ADD KEY `fk_usergroup` (`userid`);

--
-- Indexes for table `z_evote_mst_hima`
--
ALTER TABLE `z_evote_mst_hima`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`);

--
-- Indexes for table `z_evote_mst_presiden_bem`
--
ALTER TABLE `z_evote_mst_presiden_bem`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`);

--
-- Indexes for table `z_evote_mst_tahun_periode`
--
ALTER TABLE `z_evote_mst_tahun_periode`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `z_evote_trs_vote`
--
ALTER TABLE `z_evote_trs_vote`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ms_presiden_bem` (`id_ms_presiden_bem`),
  ADD KEY `id_ms_hima` (`id_ms_hima`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `z_evote_mst_hima`
--
ALTER TABLE `z_evote_mst_hima`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `z_evote_mst_presiden_bem`
--
ALTER TABLE `z_evote_mst_presiden_bem`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `z_evote_mst_tahun_periode`
--
ALTER TABLE `z_evote_mst_tahun_periode`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `z_evote_trs_vote`
--
ALTER TABLE `z_evote_trs_vote`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sc_userrole`
--
ALTER TABLE `sc_userrole`
  ADD CONSTRAINT `fk_groupuser` FOREIGN KEY (`idrole`) REFERENCES `sc_role` (`idrole`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usergroup` FOREIGN KEY (`userid`) REFERENCES `sc_user` (`userid`) ON UPDATE CASCADE;

--
-- Constraints for table `z_evote_trs_vote`
--
ALTER TABLE `z_evote_trs_vote`
  ADD CONSTRAINT `z_evote_trs_vote_ibfk_1` FOREIGN KEY (`id_ms_presiden_bem`) REFERENCES `z_evote_mst_presiden_bem` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `z_evote_trs_vote_ibfk_2` FOREIGN KEY (`id_ms_hima`) REFERENCES `z_evote_mst_hima` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
