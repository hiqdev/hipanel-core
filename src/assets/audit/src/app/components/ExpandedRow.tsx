import React, { useState } from "react";
import { Button, Space, Table, Tag, Typography } from "antd";
import { Differ, Viewer } from "json-diff-kit";
import "json-diff-kit/dist/viewer.css";
import type { DataType } from "../types";
import { colors } from "../utils";

const { Text } = Typography;

const differ = new Differ({
  detectCircular: true,    // default `true`
  maxDepth: Infinity,      // default `Infinity`
  showModifications: true, // default `true`
  arrayDiffMethod: "lcs",  // default `"normal"`, but `"lcs"` may be more useful
});

interface FieldChange {
  key: string;
  field: string;
  before: unknown;
  after: unknown;
}

const isSet = (value: unknown): boolean => value !== undefined && value !== null;
const stringify = (value: unknown): string => (typeof value === "object" ? JSON.stringify(value) : String(value));

// Fields that differ; for create/delete (one side is empty) every field of the record is listed.
const collectChanges = ({ old, new: next }: DataType["diff"]): FieldChange[] =>
  Array.from(new Set([...Object.keys(old ?? {}), ...Object.keys(next ?? {})]))
    .filter((field) => !old || !next || JSON.stringify(old[field]) !== JSON.stringify(next[field]))
    .map((field) => ({ key: field, field, before: old?.[field], after: next?.[field] }));

const Value = ({ value, color }: { value: unknown; color?: string }) => {
  if (!isSet(value)) {
    return <Text type={"secondary"}>—</Text>;
  }
  if (value === "") {
    return <Text type={"secondary"} italic>(empty)</Text>;
  }
  const text = stringify(value);

  return <Text ellipsis={{ tooltip: text }} style={{ maxWidth: 420, color }}>{text}</Text>;
};

const changeColumns = [
  { title: "Field", dataIndex: "field", key: "field", width: 200, render: (field: string) => <Text strong>{field}</Text> },
  { title: "Before", dataIndex: "before", key: "before", render: (value: unknown, row: FieldChange) => <Value value={value} color={isSet(value) && isSet(row.after) ? "#cf1322" : undefined} /> },
  { title: "After", dataIndex: "after", key: "after", render: (value: unknown, row: FieldChange) => <Value value={value} color={isSet(value) ? "#389e0d" : undefined} /> },
];

export const ExpandedRow = ({ record }: { record: DataType }) => {
  const [showDiff, setShowDiff] = useState(false);
  const { old, new: next } = record.diff;
  const changes = collectChanges(record.diff);
  const isRecordEvent = !old || !next;
  const meta = Object.entries({ IP: record.request?.ip, App: record.request?.app, "Log ID": record.request?.log_id })
    .filter(([, value]) => isSet(value));

  return (
    <Space direction={"vertical"} size={"small"} style={{ width: "100%" }}>
      <Space size={"small"} wrap>
        {!old && <Tag color={colors.create}>Record created</Tag>}
        {!next && <Tag color={colors.delete}>Record deleted</Tag>}
        {meta.map(([label, value]) => <Tag key={label}>{label}: {String(value)}</Tag>)}
      </Space>
      <Table<FieldChange>
        size={"small"}
        bordered
        pagination={false}
        columns={changeColumns}
        dataSource={changes}
        locale={{ emptyText: "No field changes" }}
      />
      {!isRecordEvent && (
        <>
          <div>
            <Button
              size={"small"}
              type={"primary"}
              ghost
              icon={<span className={"fa fa-code fa-fw"}></span>}
              onClick={() => setShowDiff(!showDiff)}
            >
              {showDiff ? "Hide full diff" : "Show full diff"}
            </Button>
          </div>
          {showDiff && (
            <Viewer
              diff={differ.diff(old, next)}
              indent={4}                 // default `2`
              lineNumbers={true}         // default `false`
              highlightInlineDiff={true} // default `false`
              inlineDiffOptions={{
                mode: "word",            // default `"char"`, but `"word"` may be more useful
                wordSeparator: " ",      // default `""`, but `" "` is more useful for sentences
              }}
            />
          )}
        </>
      )}
    </Space>
  );
};
