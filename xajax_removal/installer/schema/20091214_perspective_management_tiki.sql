INSERT INTO `users_permissions` (`permName`, `permDesc`, `level`, `type`, `admin`, `feature_check`) VALUES('tiki_p_perspective_edit', 'Can edit the perspective', 'admin', 'perspective', NULL, 'feature_perspective');
INSERT INTO `users_permissions` (`permName`, `permDesc`, `level`, `type`, `admin`, `feature_check`) VALUES('tiki_p_perspective_create', 'Can create a perspective', 'admin', 'perspective', NULL, 'feature_perspective');
INSERT INTO `users_permissions` (`permName`, `permDesc`, `level`, `type`, `admin`, `feature_check`) VALUES('tiki_p_perspective_admin', 'Can admin perspectives', 'admin', 'perspective', 'y', 'feature_perspective');
