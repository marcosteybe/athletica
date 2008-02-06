-- 
-- Datenbank: 'athletica'
-- 

-- --------------------------------------------------------

DROP TABLE IF EXISTS land;
CREATE TABLE land (
  xCode char(3) NOT NULL default '',
  Name varchar(100) NOT NULL default '',
  Sortierwert int(11) NOT NULL default '0',
  PRIMARY KEY  (xCode)
) TYPE=MyISAM;

INSERT INTO land (xCode, Name, Sortierwert) VALUES 
('SUI', 'Switzerland', 1),
('AFG', 'Afghanistan', 2),
('ALB', 'Albania', 3),
('ALG', 'Algeria', 4),
('ASA', 'American Samoa', 5),
('AND', 'Andorra', 6),
('ANG', 'Angola', 7),
('AIA', 'Anguilla', 8),
('ANT', 'Antigua & Barbuda', 9),
('ARG', 'Argentina', 10),
('ARM', 'Armenia', 11),
('ARU', 'Aruba', 12),
('AUS', 'Australia', 13),
('AUT', 'Austria', 14),
('AZE', 'Azerbaijan', 15),
('BAH', 'Bahamas', 16),
('BRN', 'Bahrain', 17),
('BAN', 'Bangladesh', 18),
('BAR', 'Barbados', 19),
('BLR', 'Belarus', 20),
('BEL', 'Belgium', 21),
('BIZ', 'Belize', 22),
('BEN', 'Benin', 23),
('BER', 'Bermuda', 24),
('BHU', 'Bhutan', 25),
('BOL', 'Bolivia', 26),
('BIH', 'Bosnia Herzegovina', 27),
('BOT', 'Botswana', 28),
('BRA', 'Brazil', 29),
('BRU', 'Brunei', 30),
('BUL', 'Bulgaria', 31),
('BRK', 'Burkina Faso', 32),
('BDI', 'Burundi', 33),
('CAM', 'Cambodia', 34),
('CMR', 'Cameroon', 35),
('CAN', 'Canada', 36),
('CPV', 'Cape Verde Islands', 37),
('CAY', 'Cayman Islands', 38),
('CAF', 'Central African Republic', 39),
('CHA', 'Chad', 40),
('CHI', 'Chile', 41),
('CHN', 'China', 42),
('COL', 'Colombia', 43),
('COM', 'Comoros', 44),
('CGO', 'Congo', 45),
('COD', 'Congo [Zaire]', 46),
('COK', 'Cook Islands', 47),
('CRC', 'Costa Rica', 48),
('CIV', 'Ivory Coast', 49),
('CRO', 'Croatia', 50),
('CUB', 'Cuba', 51),
('CYP', 'Cyprus', 52),
('CZE', 'Czech Republic', 53),
('DEN', 'Denmark', 54),
('DJI', 'Djibouti', 55),
('DMA', 'Dominica', 56),
('DOM', 'Dominican Republic', 57),
('TLS', 'East Timor', 58),
('ECU', 'Ecuador', 59),
('EGY', 'Egypt', 60),
('ESA', 'El Salvador', 61),
('GEQ', 'Equatorial Guinea', 62),
('ERI', 'Eritrea', 63),
('EST', 'Estonia', 64),
('ETH', 'Ethiopia', 65),
('FIJ', 'Fiji', 66),
('FIN', 'Finland', 67),
('FRA', 'France', 68),
('GAB', 'Gabon', 69),
('GAM', 'Gambia', 70),
('GEO', 'Georgia', 71),
('GER', 'Germany', 72),
('GHA', 'Ghana', 73),
('GIB', 'Gibraltar', 74),
('GBR', 'Great Britain & NI', 75),
('GRE', 'Greece', 76),
('GRN', 'Grenada', 77),
('GUM', 'Guam', 78),
('GUA', 'Guatemala', 79),
('GUI', 'Guinea', 80),
('GBS', 'Guinea-Bissau', 81),
('GUY', 'Guyana', 82),
('HAI', 'Haiti', 83),
('HON', 'Honduras', 84),
('HKG', 'Hong Kong', 85),
('HUN', 'Hungary', 86),
('ISL', 'Iceland', 87),
('IND', 'India', 88),
('INA', 'Indonesia', 89),
('IRI', 'Iran', 90),
('IRQ', 'Iraq', 91),
('IRL', 'Ireland', 92),
('ISR', 'Israel', 93),
('ITA', 'Italy', 94),
('JAM', 'Jamaica', 95),
('JPN', 'Japan', 96),
('JOR', 'Jordan', 97),
('KAZ', 'Kazakhstan', 98),
('KEN', 'Kenya', 99),
('KIR', 'Kiribati', 100),
('KOR', 'Korea', 101),
('KUW', 'Kuwait', 102),
('KGZ', 'Kirgizstan', 103),
('LAO', 'Laos', 104),
('LAT', 'Latvia', 105),
('LIB', 'Lebanon', 106),
('LES', 'Lesotho', 107),
('LBR', 'Liberia', 108),
('LIE', 'Liechtenstein', 109),
('LTU', 'Lithuania', 110),
('LUX', 'Luxembourg', 111),
('LBA', 'Libya', 112),
('MAC', 'Macao', 113),
('MKD', 'Macedonia', 114),
('MAD', 'Madagascar', 115),
('MAW', 'Malawi', 116),
('MAS', 'Malaysia', 117),
('MDV', 'Maldives', 118),
('MLI', 'Mali', 119),
('MLT', 'Malta', 120),
('MSH', 'Marshall Islands', 121),
('MTN', 'Mauritania', 122),
('MRI', 'Mauritius', 123),
('MEX', 'Mexico', 124),
('FSM', 'Micronesia', 125),
('MDA', 'Moldova', 126),
('MON', 'Monaco', 127),
('MGL', 'Mongolia', 128),
('MNE', 'Montenegro', 129),
('MNT', 'Montserrat', 130),
('MAR', 'Morocco', 131),
('MOZ', 'Mozambique', 132),
('MYA', 'Myanmar [Burma]', 133),
('NAM', 'Namibia', 134),
('NRU', 'Nauru', 135),
('NEP', 'Nepal', 136),
('NED', 'Netherlands', 137),
('AHO', 'Netherlands Antilles', 138),
('NZL', 'New Zealand', 139),
('NCA', 'Nicaragua', 140),
('NIG', 'Niger', 141),
('NGR', 'Nigeria', 142),
('NFI', 'Norfolk Islands', 143),
('PRK', 'North Korea', 144),
('NOR', 'Norway', 145),
('OMN', 'Oman', 146),
('PAK', 'Pakistan', 147),
('PLW', 'Palau', 148),
('PLE', 'Palestine', 149),
('PAN', 'Panama', 150),
('NGU', 'Papua New Guinea', 151),
('PAR', 'Paraguay', 152),
('PER', 'Peru', 153),
('PHI', 'Philippines', 154),
('POL', 'Poland', 155),
('POR', 'Portugal', 156),
('PUR', 'Puerto Rico', 157),
('QAT', 'Qatar', 158),
('ROM', 'Romania', 159),
('RUS', 'Russia', 160),
('RWA', 'Rwanda', 161),
('SMR', 'San Marino', 162),
('STP', 'S�o Tome & Princip�', 163),
('KSA', 'Saudi Arabia', 164),
('SEN', 'Senegal', 165),
('SRB', 'Serbia', 166),
('SEY', 'Seychelles', 167),
('SLE', 'Sierra Leone', 168),
('SIN', 'Singapore', 169),
('SVK', 'Slovakia', 170),
('SLO', 'Slovenia', 171),
('SOL', 'Solomon Islands', 172),
('SOM', 'Somalia', 173),
('RSA', 'South Africa', 174),
('ESP', 'Spain', 175),
('SKN', 'St. Kitts & Nevis', 176),
('SRI', 'Sri Lanka', 177),
('LCA', 'St. Lucia', 178),
('VIN', 'St. Vincent & the Grenadines', 179),
('SUD', 'Sudan', 180),
('SUR', 'Surinam', 181),
('SWZ', 'Swaziland', 182),
('SWE', 'Sweden', 183),
('SYR', 'Syria', 185),
('TAH', 'Tahiti', 186),
('TPE', 'Taiwan', 187),
('TAD', 'Tadjikistan', 188),
('TAN', 'Tanzania', 189),
('THA', 'Thailand', 190),
('TOG', 'Togo', 191),
('TGA', 'Tonga', 192),
('TRI', 'Trinidad & Tobago', 193),
('TUN', 'Tunisia', 194),
('TUR', 'Turkey', 195),
('TKM', 'Turkmenistan', 196),
('TKS', 'Turks & Caicos Islands', 197),
('UGA', 'Uganda', 198),
('UKR', 'Ukraine', 199),
('UAE', 'United Arab Emirates', 200),
('USA', 'United States', 201),
('URU', 'Uruguay', 202),
('UZB', 'Uzbekistan', 203),
('VAN', 'Vanuatu', 204),
('VEN', 'Venezuela', 205),
('VIE', 'Vietnam', 206),
('ISV', 'Virgin Islands', 207),
('SAM', 'Western Samoa', 208),
('YEM', 'Yemen', 209),
('ZAM', 'Zambia', 210),
('ZIM', 'Zimbabwe', 211);