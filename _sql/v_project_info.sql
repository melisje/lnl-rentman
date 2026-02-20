CREATE OR REPLACE VIEW v_projects AS
SELECT
  prj.id
  , prj.account
  , prj.rm_id
  , prj.number
  , prj.displayname
  , prj.account_manager
  , prj.project_manager
  , prj.updated_at

FROM `rm_projects` prj
WHERE 1=1
  AND prj.project_manager IS NOT NULL

ORDER BY
  prj.updated_at DESC