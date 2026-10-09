import React, { useEffect, useMemo, useState } from "react";
import { Table } from "antd";
import "./App.css";
import type { DataType, Filters } from "./types";
import { emptyFilters, groupByTrace, isEmpty, matches, toMs } from "./utils";
import { columns } from "./columns";
import { ExpandedRow } from "./components/ExpandedRow";
import { FilterBar } from "./components/FilterBar";
import { Help } from "./components/Help";

export default function App() {
  const dataSource = useMemo(() => {
    const rows = JSON.parse(window["__audit_data__"] || "[]") as DataType[];

    return rows.sort((a, b) => toMs(b.timestamp) - toMs(a.timestamp));
  }, []);
  const [filters, setFilters] = useState<Filters>(emptyFilters);
  const tableData = useMemo(() => groupByTrace(dataSource.filter((row) => matches(row, filters))), [dataSource, filters]);
  const users = useMemo(() => Array.from(new Set(dataSource.map(({ user }) => user?.login).filter((v): v is string => !!v))), [dataSource]);
  const apps = useMemo(() => Array.from(new Set(dataSource.map(({ request }) => request?.app).filter((v): v is string => !!v))), [dataSource]);
  const version = window.location.hash.substring(1);
  useEffect(() => {
    if (version) {
      document.getElementById(`#${version}`)?.scrollIntoView({ block: "center" });
    }
  }, []);
  const foundItem = version ? dataSource.find((item) => item.id === version) : undefined;

  return (
    <>
      <Help />
      <FilterBar filters={filters} users={users} apps={apps} onChange={setFilters} />
      <Table<DataType>
        size={"small"}
        columns={columns}
        dataSource={tableData}
        rowClassName={(record) => (record.kind === "group" ? "audit-group-head" : record.inGroup ? "audit-group-child" : "")}
        pagination={{ pageSize: 100 }}
        expandable={{
          expandedRowRender: (record) => <ExpandedRow record={record} />,
          defaultExpandedRowKeys: foundItem ? [foundItem.key] : [],
          rowExpandable: (record) => record.kind !== "group" && (!isEmpty(record.diff.old) || !isEmpty(record.diff.new)),
        }}
      />
    </>
  );
}
