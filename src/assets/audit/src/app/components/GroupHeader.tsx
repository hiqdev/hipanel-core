import React from "react";
import { Space, Typography } from "antd";
import type { DataType } from "../types";
import { formatTimestamp, shortTrace } from "../utils";
import { OperationTag, Restricted } from "./common";

const { Text } = Typography;

export const GroupHeader = ({ group }: { group: DataType }) => {
  const rows = group.rows ?? [];
  const counts = rows.reduce<Record<string, number>>((acc, { operation }) => ({ ...acc, [operation]: (acc[operation] ?? 0) + 1 }), {});
  const traceId = group.request?.trace_id;

  return (
    <Space size={"middle"} wrap>
      <span className={"fa fa-code-fork fa-fw"}></span>
      <Text strong>{rows.length} changes in one request</Text>
      <Text type={"secondary"}>{formatTimestamp(group.timestamp)}</Text>
      {group.user?.link ? <a href={group.user.link} target={"_blank"}>{group.user.login}</a> : <Restricted />}
      <span>{Object.entries(counts).map(([operation, count]) => <OperationTag key={operation} operation={operation} count={count} />)}</span>
      {group.request?.link
        ? <a href={group.request.link}>{shortTrace(traceId)}</a>
        : <Restricted />}
    </Space>
  );
};
