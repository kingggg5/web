USE [warz]
GO

/****** Object:  Table [dbo].[WZ_FACEBOOK]    Script Date: 03/12/2018 04:10:58 ******/
IF  EXISTS (SELECT * FROM sys.objects WHERE object_id = OBJECT_ID(N'[dbo].[WZ_FACEBOOK]') AND type in (N'U'))
DROP TABLE [dbo].[WZ_FACEBOOK]
GO

USE [warz]
GO

/****** Object:  Table [dbo].[WZ_FACEBOOK]    Script Date: 03/12/2018 04:10:58 ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

SET ANSI_PADDING ON
GO

CREATE TABLE [dbo].[WZ_FACEBOOK](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[email] [varchar](128) NOT NULL,
	[name] [varchar](128) NULL,
	[ip] [varchar](40) NULL,
	[fb_id] [varchar](128) NULL,
	[timer] [varchar](128) NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]

GO

SET ANSI_PADDING OFF
GO


