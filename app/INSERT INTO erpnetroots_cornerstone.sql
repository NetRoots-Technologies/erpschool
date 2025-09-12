INSERT INTO erpnetroots_cornerstone.groups (name,`number`,code,`level`,parent_id,account_type_id,status,parent_type,created_by,updated_by,deleted_by,created_at,updated_at,deleted_at,parent_type_id) VALUES
	('Assets','1','1',1,0,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
	('Liabilities','2','2',1,0,2,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
	('Income','3','3',1,0,3,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
	('Expenses','4','4',1,0,4,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
	('Current Assets','1-01',NULL,2,1,1,1,NULL,1,1,NULL,'2024-12-20 13:03:12','2024-12-20 13:03:12',NULL,NULL),
	('Non Current Assets','1-02',NULL,2,1,1,1,NULL,1,1,NULL,'2024-12-20 13:03:57','2024-12-20 13:03:57',NULL,NULL),
	('Physical Assets','1-02-001',NULL,3,6,1,1,NULL,1,1,NULL,'2024-12-20 13:04:50','2024-12-20 13:04:50',NULL,NULL),
	('Accumulated Depreciation','1-02-002',NULL,3,6,1,1,NULL,1,1,NULL,'2024-12-20 13:06:26','2024-12-20 13:06:26',NULL,NULL),
	('Right of Use of Asset','1-02-003',NULL,3,6,1,1,NULL,1,1,NULL,'2024-12-20 13:07:15','2024-12-20 13:07:15',NULL,NULL),
	('Long term Assets','1-02-004',NULL,3,6,1,1,NULL,1,1,NULL,'2024-12-20 13:07:26','2024-12-20 13:07:26',NULL,NULL);
INSERT INTO erpnetroots_cornerstone.groups (name,`number`,code,`level`,parent_id,account_type_id,status,parent_type,created_by,updated_by,deleted_by,created_at,updated_at,deleted_at,parent_type_id) VALUES
	('Fee Receivable','1-01-001',NULL,3,5,1,1,NULL,1,1,NULL,'2024-12-20 13:07:42','2024-12-20 13:07:42',NULL,NULL),
	('Advances, Deposits and Prepayments','1-01-002',NULL,3,5,1,1,NULL,1,1,NULL,'2024-12-20 13:08:10','2024-12-20 13:08:10',NULL,NULL),
	('Due From Associated Undertakings','1-01-003',NULL,3,5,1,1,NULL,1,1,NULL,'2024-12-20 13:08:26','2024-12-20 13:08:26',NULL,NULL),
	('Loan to Directors','1-01-004',NULL,3,5,1,1,NULL,1,1,NULL,'2024-12-20 13:08:41','2024-12-20 13:08:41',NULL,NULL),
	('Cash','1-01-005',NULL,3,5,1,1,NULL,1,1,NULL,'2024-12-20 13:08:51','2024-12-20 13:08:51',NULL,NULL),
	('Banks','1-01-006',NULL,3,5,1,1,NULL,1,1,NULL,'2024-12-20 13:08:56','2024-12-20 13:08:56',NULL,NULL),
	('Share Capital & Equity','2-01',NULL,2,2,2,1,NULL,1,1,NULL,'2024-12-20 13:10:33','2024-12-20 13:10:33',NULL,NULL),
	('Share Capital','2-01-001',NULL,3,17,2,1,NULL,1,1,NULL,'2024-12-20 13:11:00','2024-12-20 13:11:00',NULL,NULL),
	('Loan From Directors','2-01-002',NULL,3,17,2,1,NULL,1,1,NULL,'2024-12-20 13:11:29','2024-12-20 13:11:29',NULL,NULL),
	('Retained Earning / Accumulated Profit','2-01-003',NULL,3,17,2,1,NULL,1,1,NULL,'2024-12-20 13:11:48','2024-12-20 13:11:48',NULL,NULL);
INSERT INTO erpnetroots_cornerstone.groups (name,`number`,code,`level`,parent_id,account_type_id,status,parent_type,created_by,updated_by,deleted_by,created_at,updated_at,deleted_at,parent_type_id) VALUES
	('Non Current Liabilities','2-02',NULL,2,2,2,1,NULL,1,1,NULL,'2024-12-20 13:12:17','2024-12-20 13:12:17',NULL,NULL),
	('Long Term Loan','2-02-001',NULL,3,21,2,1,NULL,1,1,NULL,'2024-12-20 13:12:28','2024-12-20 13:12:28',NULL,NULL),
	('Deferred Tax','2-02-002',NULL,3,21,2,1,NULL,1,1,NULL,'2024-12-20 13:12:36','2024-12-20 13:12:36',NULL,NULL),
	('Lease Liability','2-02-003',NULL,3,21,2,1,NULL,1,1,NULL,'2024-12-20 13:12:44','2024-12-20 13:12:44',NULL,NULL),
	('Long Term Security Deposits','2-02-004',NULL,3,21,2,1,NULL,1,1,NULL,'2024-12-20 13:12:51','2024-12-20 13:12:51',NULL,NULL),
	('Current Liabilities','2-03',NULL,2,2,2,1,NULL,1,1,NULL,'2024-12-20 13:13:17','2024-12-20 13:13:17',NULL,NULL),
	('Short Term Loan','2-03-001',NULL,3,26,2,1,NULL,1,1,NULL,'2024-12-20 13:13:31','2024-12-20 13:13:31',NULL,NULL),
	('Provision for Taxation','2-03-002',NULL,3,26,2,1,NULL,1,1,NULL,'2024-12-20 13:13:38','2024-12-20 13:13:38',NULL,NULL),
	('Trade and Other Payables','2-03-003',NULL,3,26,2,1,NULL,1,1,NULL,'2024-12-20 13:13:46','2024-12-20 13:13:46',NULL,NULL),
	('Advance Fee Received','2-03-004',NULL,3,26,2,1,NULL,1,1,NULL,'2024-12-20 13:13:58','2024-12-20 13:13:58',NULL,NULL);
INSERT INTO erpnetroots_cornerstone.groups (name,`number`,code,`level`,parent_id,account_type_id,status,parent_type,created_by,updated_by,deleted_by,created_at,updated_at,deleted_at,parent_type_id) VALUES
	('Due To Associated Undertakings','2-03-005',NULL,3,26,2,1,NULL,1,1,NULL,'2024-12-20 13:14:07','2024-12-20 13:14:07',NULL,NULL),
	('Fee Income','3-01',NULL,2,3,3,1,NULL,1,1,NULL,'2024-12-20 13:16:58','2024-12-20 13:16:58',NULL,NULL),
	('Fee Income','3-01-001',NULL,3,32,3,1,NULL,1,1,NULL,'2024-12-20 13:17:34','2024-12-20 13:17:34',NULL,NULL),
	('Fee Heads Income','3-01-001-0001',NULL,4,33,3,1,NULL,1,1,NULL,'2024-12-20 13:17:51','2024-12-20 13:17:51',NULL,NULL),
	('Other Income','3-02',NULL,2,3,3,1,NULL,1,1,NULL,'2024-12-20 13:18:10','2024-12-20 13:18:10',NULL,NULL),
	('Other Income','3-02-001',NULL,3,35,3,1,NULL,1,1,NULL,'2024-12-20 13:18:27','2024-12-20 13:18:27',NULL,NULL),
	('Other Income','3-02-001-0001',NULL,4,36,3,1,NULL,1,1,NULL,'2024-12-20 13:18:44','2024-12-20 13:18:44',NULL,NULL),
	('Administrative Expenses','4-01',NULL,2,4,4,1,NULL,1,1,NULL,'2024-12-20 13:19:04','2024-12-20 13:19:04',NULL,NULL),
	('Finance Cost','4-02',NULL,2,4,4,1,NULL,1,1,NULL,'2024-12-20 13:19:37','2024-12-20 13:19:37',NULL,NULL),
	('Taxation','4-03',NULL,2,4,4,1,NULL,1,1,NULL,'2024-12-20 13:19:48','2024-12-20 13:19:48',NULL,NULL);
INSERT INTO erpnetroots_cornerstone.groups (name,`number`,code,`level`,parent_id,account_type_id,status,parent_type,created_by,updated_by,deleted_by,created_at,updated_at,deleted_at,parent_type_id) VALUES
	('Freight and Transportation','4-04',NULL,2,4,4,1,NULL,1,1,NULL,'2024-12-20 13:20:01','2024-12-20 13:20:01',NULL,NULL),
	('Director Remuneration','4-01-001',NULL,3,38,4,1,NULL,1,1,NULL,'2024-12-20 13:21:06','2024-12-20 13:21:06',NULL,NULL),
	('Salaries, wages and benefits','4-01-002',NULL,3,38,4,1,NULL,1,1,NULL,'2024-12-20 13:21:17','2024-12-20 13:21:17',NULL,NULL),
	('Utilities Expense','4-01-003',NULL,3,38,4,1,NULL,1,1,NULL,'2024-12-20 13:21:25','2024-12-20 13:21:25',NULL,NULL),
	('Rent, rates and taxes','4-01-004',NULL,3,38,4,1,NULL,1,1,NULL,'2024-12-20 13:21:39','2024-12-20 13:21:39',NULL,NULL),
	('Repair and Maintenance','4-01-005',NULL,3,38,4,1,NULL,1,1,NULL,'2024-12-20 13:21:46','2024-12-20 13:21:46',NULL,NULL),
	('Printing and Stationary','4-01-006',NULL,3,38,4,1,NULL,1,1,NULL,'2024-12-20 13:21:55','2024-12-20 13:21:55',NULL,NULL),
	('Depreciation','4-01-007',NULL,3,38,4,1,NULL,1,1,NULL,'2024-12-20 13:22:04','2024-12-20 13:22:04',NULL,NULL),
	('Postage, courier and customs','4-01-008',NULL,3,38,4,1,NULL,1,1,NULL,'2024-12-20 13:22:12','2024-12-20 13:22:12',NULL,NULL),
	('Events and functions','4-01-009',NULL,3,38,4,1,NULL,1,1,NULL,'2024-12-20 13:23:31','2024-12-20 13:23:31',NULL,NULL);
INSERT INTO erpnetroots_cornerstone.groups (name,`number`,code,`level`,parent_id,account_type_id,status,parent_type,created_by,updated_by,deleted_by,created_at,updated_at,deleted_at,parent_type_id) VALUES
	('Teachers Training','4-01-010',NULL,3,38,4,1,NULL,1,1,NULL,'2024-12-20 13:23:39','2024-12-20 13:23:39',NULL,NULL),
	('Entertainment','4-01-011',NULL,3,38,4,1,NULL,1,1,NULL,'2024-12-20 13:23:45','2024-12-20 13:23:45',NULL,NULL),
	('Advertisement','4-01-012',NULL,3,38,4,1,NULL,1,1,NULL,'2024-12-20 13:23:54','2024-12-20 13:23:54',NULL,NULL),
	('Traveling and lodging','4-01-013',NULL,3,38,4,1,NULL,1,1,NULL,'2024-12-20 13:24:03','2024-12-20 13:24:03',NULL,NULL),
	('Bank charges','4-01-014',NULL,3,38,4,1,NULL,1,1,NULL,'2024-12-20 13:24:29','2024-12-20 13:24:29',NULL,NULL),
	('Communication','4-01-015',NULL,3,38,4,1,NULL,1,1,NULL,'2024-12-20 13:24:40','2024-12-20 13:24:40',NULL,NULL),
	('Auditor''s remuneration','4-01-016',NULL,3,38,4,1,NULL,1,1,NULL,'2024-12-20 13:24:48','2024-12-20 13:24:48',NULL,NULL),
	('Vehicle and generator','4-01-017',NULL,3,38,4,1,NULL,1,1,NULL,'2024-12-20 13:24:57','2024-12-20 13:24:57',NULL,NULL),
	('Insurance','4-01-018',NULL,3,38,4,1,NULL,1,1,NULL,'2024-12-20 13:25:03','2024-12-20 13:25:03',NULL,NULL),
	('Legal and professional','4-01-019',NULL,3,38,4,1,NULL,1,1,NULL,'2024-12-20 13:25:15','2024-12-20 13:25:15',NULL,NULL);
INSERT INTO erpnetroots_cornerstone.groups (name,`number`,code,`level`,parent_id,account_type_id,status,parent_type,created_by,updated_by,deleted_by,created_at,updated_at,deleted_at,parent_type_id) VALUES
	('Exam/registration','4-01-020',NULL,3,38,4,1,NULL,1,1,NULL,'2024-12-20 13:25:24','2024-12-20 13:25:24',NULL,NULL),
	('Laboratory materials','4-01-021',NULL,3,38,4,1,NULL,1,1,NULL,'2024-12-20 13:25:34','2024-12-20 13:25:34',NULL,NULL),
	('ERP maintenance','4-01-022',NULL,3,38,4,1,NULL,1,1,NULL,'2024-12-20 13:25:43','2024-12-20 13:25:43',NULL,NULL),
	('Office supplies','4-01-023',NULL,3,38,4,1,NULL,1,1,NULL,'2024-12-20 13:25:53','2024-12-20 13:25:53',NULL,NULL),
	('Safety and security','4-01-024',NULL,3,38,4,1,NULL,1,1,NULL,'2024-12-20 13:26:09','2024-12-20 13:26:09',NULL,NULL),
	('Miscellaneous Expense','4-01-025',NULL,3,38,4,1,NULL,1,1,NULL,'2024-12-20 13:26:19','2024-12-20 13:26:19',NULL,NULL),
	('Fee and Subscription','4-01-026',NULL,3,38,4,1,NULL,1,1,NULL,'2024-12-20 13:26:27','2024-12-20 13:26:27',NULL,NULL),
	('Suspense Account','4-01-027',NULL,3,38,4,1,NULL,1,1,NULL,'2024-12-20 13:26:45','2024-12-20 13:26:45',NULL,NULL),
	('Freight and Transportation','4-01-028',NULL,3,38,4,1,NULL,1,1,NULL,'2024-12-20 13:26:51','2024-12-20 13:26:51',NULL,NULL),
	('Finance Cost','4-05',NULL,2,4,4,1,NULL,1,1,NULL,'2024-12-20 13:27:13','2024-12-20 13:27:13',NULL,NULL);
INSERT INTO erpnetroots_cornerstone.groups (name,`number`,code,`level`,parent_id,account_type_id,status,parent_type,created_by,updated_by,deleted_by,created_at,updated_at,deleted_at,parent_type_id) VALUES
	('Taxation','4-06',NULL,2,4,4,1,NULL,1,1,NULL,'2024-12-20 13:27:21','2024-12-20 13:27:21',NULL,NULL),
	('Asset Heads','1-02-001-0001',NULL,4,7,1,1,NULL,1,1,NULL,'2024-12-23 06:06:48','2024-12-23 06:06:48',NULL,NULL),
	('Cash','1-01-005-0001',NULL,4,15,1,1,NULL,1,1,NULL,'2024-12-23 06:51:46','2024-12-23 06:51:46',NULL,NULL),
	('Cash In hand','1-01-005-0001-00001',NULL,5,73,1,1,NULL,1,1,NULL,'2024-12-23 06:52:34','2024-12-23 06:52:34',NULL,NULL),
	('Fee Heads Receivable','1-01-001-0001',NULL,4,11,1,1,NULL,1,1,NULL,'2024-12-24 08:28:36','2024-12-24 08:28:36',NULL,NULL),
	('Accumulated Depreciation Asset Heads','1-02-002-0001',NULL,4,8,1,1,NULL,1,1,NULL,'2024-12-24 10:50:41','2024-12-24 10:50:41',NULL,NULL);