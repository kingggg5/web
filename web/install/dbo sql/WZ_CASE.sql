USE [warz]
GO

/****** Object:  Table [dbo].[WZ_CASE]    Script Date: 03/09/2018 18:56:16 ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[WZ_CASE]') AND type in (N'U'))
DROP TABLE [dbo].[WZ_CASE]
GO

USE [warz]
GO

/****** Object:  Table [dbo].[WZ_CASE]    Script Date: 03/09/2018 18:56:16 ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

SET ANSI_PADDING ON
GO

CREATE TABLE [dbo].[WZ_CASE](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[email] [varchar](255) NOT NULL,
	[pack] [varchar](255) NULL,
	[price] [int] NULL,
	[timer] [varchar](255) NULL
) ON [PRIMARY]

GO

SET ANSI_PADDING OFF
GO


