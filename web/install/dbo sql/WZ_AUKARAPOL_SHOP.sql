USE [warz]
GO

/****** Object:  Table [dbo].[WZ_AUKARAPOL_SHOP]    Script Date: 04/24/2018 12:33:30 ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[WZ_AUKARAPOL_SHOP]') AND type in (N'U'))
DROP TABLE [dbo].[WZ_AUKARAPOL_SHOP]
GO

USE [warz]
GO

/****** Object:  Table [dbo].[WZ_AUKARAPOL_SHOP]    Script Date: 04/24/2018 12:33:30 ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

SET ANSI_PADDING ON
GO

CREATE TABLE [dbo].[WZ_AUKARAPOL_SHOP](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[name] [varchar](255) NULL,
	[picture] [varchar](255) NULL,
	[price] [int] NULL,
	[item_id] [int] NULL,
	[item_num] [int] NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]

GO

SET ANSI_PADDING OFF
GO