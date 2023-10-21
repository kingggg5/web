USE [warz]
GO

ALTER TABLE Accounts ADD case_num int NOT NULL DEFAULT(0)
GO