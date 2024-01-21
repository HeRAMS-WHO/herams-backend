import { __ } from '../../../utils/translationsUtility';
import Typography from '@mui/material/Typography';
import replaceVariablesAsText from '../../../utils/replaceVariablesAsText';
import Link from '@mui/material/Link';
import dayjs from 'dayjs';
import Button from '@mui/material/Button';
import EditIcon from '@mui/icons-material/Edit';
import React from 'react';

const HSDUIndexTableHeader = () => {
  return [
    {
      headerName: __('Id'), // Use headerName for CSV export and table display
      field: 'id',
      type: 'number',
      width: 80,
    },
    {
      headerName: __('HSDU name'),
      renderCell: (params) => (
        <Link
          href={replaceVariablesAsText(`/admin/project/:projectId/workspace/:workspaceId/HSDU/` + params.id + '/situation-update')}>{params.value}</Link>
      ),
      field: 'name',
      flex: 1,
    },
    {
      headerName: __('Date of update'),
      field: 'date_of_update',
      type: 'date',
      flex: 1,
      valueFormatter: (params) => params.value && dayjs(params.value).format('YYYY-MM-DD HH:mm'), // Fixed minute format to lowercase 'mm'
    },
    {
      headerName: __('HSDU Code'),
      field: 'HSDU_CODE',
      flex: 1,
    },
    {
      headerName: __('HSDU Type'),
      field: 'HSDU_TYPE_tier',
      flex: 1,
    },
    {
      headerName: __('Functionality'),
      field: 'P2_FUNCIONALITY',
      flex: 1,
    },
    {
      headerName: __('Accessibility'), // Fixed typo in 'Accessibility'
      field: 'P2_ACCESSIBILITY',
      flex: 1,
    },
    {
      headerName: __('Actions'),
      field: 'actions',
      type: 'actions',
      flex: 1,
      renderCell: (params) => {
        if (params.can_receive_situation_update === 0) {
          return (
            <Typography variant="caption" display="block" gutterBottom>
              {__('No updates expected')}
            </Typography>
          )
        } else {
          return (
            <Button startIcon={<EditIcon/>}
                    href={replaceVariablesAsText(`/admin/project/:projectId/workspace/:workspaceId/HSDU/${params.id}/situation-update/create`)}
                    size="small">
              {__('Update')}
            </Button>
          )
        }
      },
    },
  ]
}

export default HSDUIndexTableHeader
